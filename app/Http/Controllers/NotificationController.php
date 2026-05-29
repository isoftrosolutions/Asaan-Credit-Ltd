<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function index()
    {
        $userId = \App\Core\Auth::id();
        $notifications = \App\Core\Database::fetchAll(
            "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 30",
            [$userId]
        );
        return view('notifications.index', ['notifications' => $notifications]);
    }

    public function markRead($notification)
    {
        $userId = \App\Core\Auth::id();
        $notif = \App\Core\Database::fetch("SELECT * FROM notifications WHERE id = ?", [$notification]);
        if (!$notif || $notif->user_id !== $userId) {
            abort(403, 'Unauthorized.');
        }
        \App\Core\Database::update('notifications', ['is_read' => 1], 'id = ?', [$notification]);
        json_response(['success' => true]);
    }

    public function markAllRead()
    {
        $userId = \App\Core\Auth::id();
        \App\Core\Database::update(
            'notifications',
            ['is_read' => 1],
            'user_id = ? AND is_read = 0',
            [$userId]
        );
        back();
    }

    public function unreadCount()
    {
        $userId = \App\Core\Auth::id();
        $count = (int)\App\Core\Database::query(
            "SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0",
            [$userId]
        )->fetchColumn();
        json_response(['count' => $count]);
    }
}
