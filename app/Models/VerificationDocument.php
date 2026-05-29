<?php
namespace App\Models;

use App\Core\Database;

class VerificationDocument
{
    public static function findByUserId($userId): array
    {
        return Database::fetchAll(
            'SELECT * FROM verification_documents WHERE user_id = :user_id ORDER BY created_at DESC',
            ['user_id' => $userId]
        );
    }

    public static function pending(): array
    {
        return Database::fetchAll(
            'SELECT vd.*, u.name as user_name, u.email as user_email, u.role as user_role
             FROM verification_documents vd
             JOIN users u ON vd.user_id = u.id
             WHERE vd.status = :status
             ORDER BY vd.created_at ASC',
            ['status' => 'pending']
        );
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::insert('verification_documents', $data);
    }

    public static function approve($id, $reviewerId): void
    {
        Database::update(
            'verification_documents',
            ['status' => 'approved', 'reviewed_by' => $reviewerId, 'reviewed_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            'id = :id',
            ['id' => $id]
        );
    }

    public static function reject($id, $reviewerId, $reason): void
    {
        Database::update(
            'verification_documents',
            ['status' => 'rejected', 'reviewed_by' => $reviewerId, 'reviewed_at' => date('Y-m-d H:i:s'), 'rejection_reason' => $reason, 'updated_at' => date('Y-m-d H:i:s')],
            'id = :id',
            ['id' => $id]
        );
    }
}
