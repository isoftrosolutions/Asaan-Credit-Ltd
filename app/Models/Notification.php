<?php
namespace App\Models;

use App\Core\Database;

class Notification
{
    public static function forUser($userId, $limit = 30): array
    {
        return Database::fetchAll(
            'SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit',
            ['user_id' => $userId, 'limit' => $limit]
        );
    }

    public static function unreadCount($userId): int
    {
        $result = Database::fetch(
            'SELECT COUNT(*) as count FROM notifications WHERE user_id = :user_id AND is_read = 0',
            ['user_id' => $userId]
        );
        return $result ? (int)$result->count : 0;
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::insert('notifications', $data);
    }

    public static function markRead($id, $userId): void
    {
        Database::update(
            'notifications',
            ['is_read' => 1, 'updated_at' => date('Y-m-d H:i:s')],
            'id = :id AND user_id = :user_id',
            ['id' => $id, 'user_id' => $userId]
        );
    }

    public static function markAllRead($userId): void
    {
        Database::update(
            'notifications',
            ['is_read' => 1, 'updated_at' => date('Y-m-d H:i:s')],
            'user_id = :user_id AND is_read = 0',
            ['user_id' => $userId]
        );
    }

    public static function broadcast(array $userIds, $type, $title, $body = null, $actionUrl = null): void
    {
        foreach ($userIds as $userId) {
            self::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'action_url' => $actionUrl,
                'is_read' => 0,
            ]);
        }
    }
}
