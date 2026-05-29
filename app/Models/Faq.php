<?php
namespace App\Models;

use App\Core\Database;

class Faq
{
    public static function all(): array
    {
        return Database::fetchAll(
            'SELECT * FROM faqs WHERE is_active = 1 ORDER BY sort_order ASC'
        );
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::insert('faqs', $data);
    }
}
