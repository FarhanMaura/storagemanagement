<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->paginate(20);

        // DEBUG
        \Log::info('Notification index - User notifications:', [
            'user_id' => Auth::id(),
            'total_notifications' => $notifications->total(),
            'current_page' => $notifications->currentPage()
        ]);

        return view('notifications.index', [
            'notifications' => $notifications,
            'title' => 'Notifikasi Sistem'
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            \Log::info('Notification marked as read:', ['notification_id' => $id]);
        }

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    public function markAllAsRead()
    {
        $unreadCount = Auth::user()->unreadNotifications()->count();
        Auth::user()->unreadNotifications->markAsRead();

        \Log::info('All notifications marked as read:', [
            'user_id' => Auth::id(),
            'marked_count' => $unreadCount
        ]);

        return back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }

    public function clearAll()
    {
        $notificationCount = Auth::user()->notifications()->count();
        Auth::user()->notifications()->delete();

        \Log::info('All notifications cleared:', [
            'user_id' => Auth::id(),
            'cleared_count' => $notificationCount
        ]);

        return back()->with('success', 'Semua notifikasi telah dihapus.');
    }
}
