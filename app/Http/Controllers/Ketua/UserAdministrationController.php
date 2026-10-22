<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminUserRequest;
use App\Http\Requests\UpdateAdminUserRequest;
use App\Models\Jamaah;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserAdministrationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $users = User::query()
            ->with(['role', 'jamaah'])
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($filters['role_id'] ?? null, fn ($query, $roleId) => $query->where('role_id', $roleId))
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('is_active', $status === 'active'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('user-administration.index', [
            'users' => $users,
            'roles' => Role::orderBy('display_name')->get(),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('user-administration.create', [
            'user' => new User(['is_active' => true]),
            'roles' => Role::orderBy('display_name')->get(),
            'jamaahs' => Jamaah::whereNull('user_id')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreAdminUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $jamaahId = isset($data['jamaah_id']) ? (int) $data['jamaah_id'] : null;
        unset($data['jamaah_id']);
        $this->validateJamaahRole((int) $data['role_id'], $jamaahId);
        $data['is_active'] = $request->boolean('is_active', true);

        $user = DB::transaction(function () use ($data, $jamaahId): User {
            $user = User::create($data);
            $this->syncJamaahLink($user, $jamaahId);

            return $user;
        });

        ActivityLogger::log('create_user', "Membuat akun {$user->email} dengan role {$user->roleName()}.", $user);

        return redirect()->route('user-administration.show', $user)->with('success', 'Akun berhasil dibuat.');
    }

    public function show(User $user): View
    {
        return view('user-administration.show', ['user' => $user->load(['role', 'jamaah'])]);
    }

    public function edit(User $user): View
    {
        return view('user-administration.edit', [
            'user' => $user->load('jamaah'),
            'roles' => Role::orderBy('display_name')->get(),
            'jamaahs' => Jamaah::where(function ($query) use ($user): void {
                $query->whereNull('user_id')->orWhere('user_id', $user->id);
            })->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateAdminUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        abort_if($request->user()->is($user) && (int) $data['role_id'] !== (int) $user->role_id, 403);

        if (! $request->boolean('is_active') && $request->user()->is($user)) {
            return back()->withErrors(['is_active' => 'Akun yang sedang digunakan tidak dapat dinonaktifkan.'])->withInput();
        }

        if ($this->isLastActiveChair($user, (int) $data['role_id'], $request->boolean('is_active'))) {
            return back()->withErrors(['role_id' => 'Setidaknya satu akun Ketua aktif harus dipertahankan.'])->withInput();
        }

        $jamaahId = isset($data['jamaah_id']) ? (int) $data['jamaah_id'] : null;
        unset($data['jamaah_id']);
        $this->validateJamaahRole((int) $data['role_id'], $jamaahId);
        $beforeRole = $user->roleName();

        DB::transaction(function () use ($user, $data, $jamaahId): void {
            $user->update($data);
            $this->syncJamaahLink($user, $jamaahId);
        });
        $user->refresh();

        $action = $beforeRole !== $user->roleName() ? 'change_user_role' : 'update_user';
        ActivityLogger::log($action, "Memperbarui akun {$user->email}; role saat ini {$user->roleName()}.", $user);

        return redirect()->route('user-administration.show', $user)->with('success', 'Akun berhasil diperbarui.');
    }

    public function sendPasswordReset(User $user): RedirectResponse
    {
        $status = Password::sendResetLink(['email' => $user->email]);
        ActivityLogger::log('send_user_password_reset', "Mengirim permintaan reset password untuk {$user->email}.", $user);

        return back()->with($status === Password::RESET_LINK_SENT ? 'success' : 'error', __($status));
    }

    private function syncJamaahLink(User $user, ?int $jamaahId): void
    {
        Jamaah::where('user_id', $user->id)->update(['user_id' => null]);

        if ($user->hasRole('jamaah') && $jamaahId !== null) {
            Jamaah::whereKey($jamaahId)->whereNull('user_id')->update(['user_id' => $user->id]);
        }
    }

    private function validateJamaahRole(int $roleId, ?int $jamaahId): void
    {
        if ($jamaahId !== null && Role::whereKey($roleId)->value('name') !== 'jamaah') {
            throw ValidationException::withMessages(['jamaah_id' => 'Data Jamaah hanya dapat ditautkan ke akun dengan role Jamaah.']);
        }
    }

    private function isLastActiveChair(User $user, int $newRoleId, bool $active): bool
    {
        $chairRoleId = Role::where('name', 'ketua')->value('id');
        if ($chairRoleId === null || (int) $user->role_id !== (int) $chairRoleId || ! $user->is_active) {
            return false;
        }

        if ($newRoleId === (int) $chairRoleId && $active) {
            return false;
        }

        return User::where('role_id', $chairRoleId)->where('is_active', true)->count() <= 1;
    }
}
