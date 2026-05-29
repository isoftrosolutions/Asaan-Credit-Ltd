<?php
namespace App\Models;

use App\Core\Database;

class Pitch
{
    public static function find($id): ?object
    {
        return Database::fetch(
            'SELECT p.*, u.name as user_name, u.company_name, u.province, u.district, u.verification_status,
                    s.name as sector_name
             FROM pitches p
             JOIN users u ON p.user_id = u.id
             LEFT JOIN sectors s ON p.sector_id = s.id
             WHERE p.id = :id',
            ['id' => $id]
        );
    }

    public static function all(array $filters = []): array
    {
        $sql = 'SELECT p.*, u.name as user_name, u.company_name, u.province, u.district,
                       s.name as sector_name
                FROM pitches p
                JOIN users u ON p.user_id = u.id
                LEFT JOIN sectors s ON p.sector_id = s.id
                WHERE p.is_active = 1 AND p.is_hidden = 0';
        $params = [];

        if (!empty($filters['sector_id'])) {
            $sql .= ' AND p.sector_id = :sector_id';
            $params['sector_id'] = $filters['sector_id'];
        }
        if (!empty($filters['stage'])) {
            $sql .= ' AND p.stage = :stage';
            $params['stage'] = $filters['stage'];
        }
        if (!empty($filters['search'])) {
            $sql .= ' AND (p.tagline LIKE :search OR u.company_name LIKE :search2)';
            $params['search'] = '%' . $filters['search'] . '%';
            $params['search2'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['user_id'])) {
            $sql .= ' AND p.user_id = :user_id';
            $params['user_id'] = $filters['user_id'];
        }
        
        $sql .= ' ORDER BY p.created_at DESC';
        
        return Database::fetchAll($sql, $params);
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::insert('pitches', $data);
    }

    public static function update($id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::update('pitches', $data, 'id = :id', ['id' => $id]);
    }

    public static function getActiveCount(): int
    {
        $result = Database::fetch(
            'SELECT COUNT(*) as count FROM pitches WHERE is_active = 1 AND is_hidden = 0'
        );
        return $result ? (int)$result->count : 0;
    }
}
