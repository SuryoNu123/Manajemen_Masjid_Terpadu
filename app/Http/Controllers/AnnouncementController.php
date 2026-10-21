<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use App\Notifications\AnnouncementPublishedNotification;
use App\Services\ActivityLogger;
use App\Services\WhatsApp\WhatsAppProviderManager;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $announcements = Announcement::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->search.'%');
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('announcements.index', compact('announcements'));
    }

    public function create(): View
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'type' => ['required', Rule::in(['normal', 'emergency'])],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'expires_at' => ['nullable', 'date'],
        ]);

        $data['author_id'] = $request->user()?->getAuthIdentifier();
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        $announcement = Announcement::create($data);

        ActivityLogger::log(
            'create_announcement',
            "Membuat pengumuman \"{$announcement->title}\" (".($announcement->type === 'emergency' ? 'Darurat' : 'Biasa').", {$announcement->status}).",
            $announcement
        );

        if ($announcement->status === 'published') {
            $this->dispatchNotifications($announcement);
        }

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function show(Announcement $announcement): View
    {
        $announcement->load(['whatsAppDeliveries.jamaah']);

        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement): View
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'type' => ['required', Rule::in(['normal', 'emergency'])],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'expires_at' => ['nullable', 'date'],
        ]);

        $wasDraft = $announcement->status === 'draft';

        $data['published_at'] = $data['status'] === 'published'
            ? ($announcement->published_at ?? now())
            : null;

        $announcement->update($data);

        ActivityLogger::log(
            'update_announcement',
            "Memperbarui pengumuman \"{$announcement->title}\" (Status: {$announcement->status}).",
            $announcement
        );

        if ($wasDraft && $announcement->status === 'published') {
            $this->dispatchNotifications($announcement);
        }

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        ActivityLogger::log(
            'delete_announcement',
            "Menghapus pengumuman \"{$announcement->title}\".",
            $announcement
        );

        $announcement->delete();

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    /**
     * Halaman index pengumuman publik untuk jamaah.
     */
    public function publicIndex(Request $request): View
    {
        $announcements = Announcement::published()
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $emergencyAnnouncements = Announcement::published()
            ->emergency()
            ->latest('published_at')
            ->get();

        return view('public.announcements.index', compact('announcements', 'emergencyAnnouncements'));
    }

    /**
     * Halaman detail pengumuman publik.
     */
    public function publicShow(Announcement $announcement): View
    {
        $announcement = Announcement::published()
            ->whereKey($announcement->getKey())
            ->firstOrFail();

        return view('public.announcements.show', compact('announcement'));
    }

    /**
     * Mengirim notifikasi in-app dan WhatsApp.
     */
    private function dispatchNotifications(Announcement $announcement): void
    {
        try {
            // Notifikasi in-app untuk semua user pengurus/jamaah yang memiliki akun
            $users = User::where('is_active', true)->get();
            if ($users->isNotEmpty()) {
                Notification::send($users, new AnnouncementPublishedNotification($announcement));
            }

            // Integrasi WhatsApp notification
            app(WhatsAppNotificationService::class)->sendAnnouncement(
                $announcement,
                app(WhatsAppProviderManager::class)
            );
        } catch (\Throwable $exception) {
            Log::error('Failed dispatching announcement notifications.', [
                'announcement_id' => $announcement->id,
                'exception' => $exception::class,
            ]);
        }
    }
}
