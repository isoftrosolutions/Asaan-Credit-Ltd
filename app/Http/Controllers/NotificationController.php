<?php
namespace App\Http\Controllers;

use App\Core\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        Auth::requireAuth();
        $user = Auth::user();
        $notifications = Notification::forUser($user->id);
        $unreadCount = Notification::unreadCount($user->id);

        return $this->render('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function markRead($id)
    {
        Auth::requireAuth();
        Notification::markRead($id, Auth::id());
        $this->json(['success' => true]);
    }

    public function markAllRead()
    {
        Auth::requireAuth();
        Notification::markAllRead(Auth::id());
        flash('success', 'All notifications marked as read.');
        $this->back();
    }

    public function unreadCount()
    {
        Auth::requireAuth();
        $count = Notification::unreadCount(Auth::id());
        $this->json(['count' => $count]);
    }
}
