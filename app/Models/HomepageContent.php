<?php

namespace App\Models;

use App\Core\Database;

class HomepageContent extends Model
{
    protected static string $table = 'homepage_contents';
    protected static array $fillable = ['key', 'value'];
    protected static array $casts = [];
    protected static array $relationConfig = [];

    public static function pluck(string $column, string $key): array
    {
        $results = Database::fetchAll(
            "SELECT {$key}, {$column} FROM " . static::$table
        );
        $plucked = [];
        foreach ($results as $row) {
            $plucked[$row->$key] = $row->$column;
        }
        return $plucked;
    }
}
