<?php
namespace App\Models;

use App\Core\Database;

class InterestRequest
{
    public static function find($id): ?object
    {
        return Database::fetch('SELECT * FROM interest_requests WHERE id = :id', ['id' => $id]);
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::insert('interest_requests', $data);
    }

    public static function update($id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::update('interest_requests', $data, 'id = :id', ['id' => $id]);
    }

    public static function getReceivedByUser($userId): array
    {
        return Database::fetchAll(
            'SELECT ir.*, u.name as sender_name, u.company_name, u.role as sender_role
             FROM interest_requests ir
             JOIN users u ON ir.sender_id = u.id
             WHERE ir.receiver_id = :receiver_id
             ORDER BY ir.created_at DESC',
            ['receiver_id' => $userId]
        );
    }

    public static function getSentByUser($userId): array
    {
        return Database::fetchAll(
            'SELECT ir.*, u.name as receiver_name, u.company_name
             FROM interest_requests ir
             JOIN users u ON ir.receiver_id = u.id
             WHERE ir.sender_id = :sender_id
             ORDER BY ir.created_at DESC',
            ['sender_id' => $userId]
        );
    }

    public static function getConnections($userId): array
    {
        return Database::fetchAll(
            'SELECT ir.*, 
                    CASE WHEN ir.sender_id = :user_id1 THEN u2.name ELSE u1.name END as connected_name,
                    CASE WHEN ir.sender_id = :user_id2 THEN u2.company_name ELSE u1.company_name END as connected_company,
                    CASE WHEN ir.sender_id = :user_id3 THEN u2.email ELSE u1.email END as connected_email,
                    CASE WHEN ir.sender_id = :user_id4 THEN u2.phone ELSE u1.phone END as connected_phone
             FROM interest_requests ir
             JOIN users u1 ON ir.sender_id = u1.id
             JOIN users u2 ON ir.receiver_id = u2.id
             WHERE (ir.sender_id = :user_id5 OR ir.receiver_id = :user_id6)
             AND ir.status = :status
             ORDER BY ir.responded_at DESC',
            [
                'user_id1' => $userId, 'user_id2' => $userId, 'user_id3' => $userId,
                'user_id4' => $userId, 'user_id5' => $userId, 'user_id6' => $userId,
                'status' => 'accepted'
            ]
        );
    }

    public static function getDailyCount($userId): int
    {
        $result = Database::fetch(
            "SELECT COUNT(*) as count FROM interest_requests 
             WHERE sender_id = :sender_id 
             AND DATE(created_at) = CURDATE()",
            ['sender_id' => $userId]
        );
        return $result ? (int)$result->count : 0;
    }

    public static function getTotalCount(): int
    {
        $result = Database::fetch('SELECT COUNT(*) as count FROM interest_requests');
        return $result ? (int)$result->count : 0;
    }

    public static function getMatchCount(): int
    {
        $result = Database::fetch(
            "SELECT COUNT(*) as count FROM interest_requests WHERE status = :status",
            ['status' => 'accepted']
        );
        return $result ? (int)$result->count : 0;
    }

    public static function allWithDetails(): array
    {
        return Database::fetchAll(
            'SELECT ir.*, s.name as sender_name, r.name as receiver_name
             FROM interest_requests ir
             JOIN users s ON ir.sender_id = s.id
             JOIN users r ON ir.receiver_id = r.id
             ORDER BY ir.created_at DESC
             LIMIT 100'
        );
    }
}
