<?php
namespace App\Models;

use App\Core\Database;

class User
{
    public static function find($id): ?object
    {
        return Database::fetch('SELECT * FROM users WHERE id = :id', ['id' => $id]);
    }

    public static function findByEmail($email): ?object
    {
        return Database::fetch('SELECT * FROM users WHERE email = :email', ['email' => $email]);
    }

    public static function all(array $filters = []): array
    {
        $sql = 'SELECT * FROM users WHERE 1=1';
        $params = [];
        
        if (!empty($filters['role'])) {
            $sql .= ' AND role = :role';
            $params['role'] = $filters['role'];
        }
        if (!empty($filters['verification_status'])) {
            $sql .= ' AND verification_status = :verification_status';
            $params['verification_status'] = $filters['verification_status'];
        }
        if (!empty($filters['search'])) {
            $sql .= ' AND (name LIKE :search OR email LIKE :search2)';
            $params['search'] = '%' . $filters['search'] . '%';
            $params['search2'] = '%' . $filters['search'] . '%';
        }
        $sql .= ' ORDER BY created_at DESC';
        
        return Database::fetchAll($sql, $params);
    }

    public static function create(array $data): int
    {
        $data['password'] = \App\Core\Auth::hash($data['password']);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::insert('users', $data);
    }

    public static function update($id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::update('users', $data, 'id = :id', ['id' => $id]);
    }

    public static function getCountByRole(): array
    {
        return Database::fetchAll(
            'SELECT role, COUNT(*) as count FROM users GROUP BY role'
        );
    }

    public static function getVerificationStats(): array
    {
        return Database::fetchAll(
            'SELECT verification_status, COUNT(*) as count FROM users GROUP BY verification_status'
        );
    }

    public static function getNewSignupsThisWeek(): int
    {
        $result = Database::fetch(
            "SELECT COUNT(*) as count FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)"
        );
        return $result ? (int)$result->count : 0;
    }

    public static function getNewSignupsThisMonth(): int
    {
        $result = Database::fetch(
            "SELECT COUNT(*) as count FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)"
        );
        return $result ? (int)$result->count : 0;
    }
}
