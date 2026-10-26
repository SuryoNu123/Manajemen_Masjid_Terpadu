<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'read' => ['nullable', 'in:read,unread'],
        ]);

        $notifications = $request->user()
            ->notifications()
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('data->title', 'like', "%{$search}%")
                        ->orWhere('data->message', 'like', "%{$search}%");
                });
            })
            ->when($filters['read'] ?? null, function (Builder $query, string $read): void {
                if ($read === 'unread') {
                    $query->whereNull('read_at');

                    return;
                }

                $query->whereNotNull('read_at');
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'notifications.index',
            compact('notifications', 'filters')
        );
    }

    public function read(string $id)
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return redirect()->back();
    }

    public function readAll()
    {
        Auth::user()
            ->unreadNotifications
            ->markAsRead();

        return redirect()
            ->back()
            ->with('success', 'Semua notifikasi telah dibaca.');
    }
}
