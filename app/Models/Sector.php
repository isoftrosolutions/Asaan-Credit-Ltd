<?php
namespace App\Models;

use App\Core\Database;

class Sector
{
    public static function all(): array
    {
        return Database::fetchAll('SELECT * FROM sectors WHERE is_active = 1 ORDER BY name');
    }

    public static function find($id): ?object
    {
        return Database::fetch('SELECT * FROM sectors WHERE id = :id', ['id' => $id]);
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::insert('sectors', $data);
    }
}
