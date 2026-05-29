<?php

namespace App\Models;

use App\Core\Database;

class Faq extends Model
{
    protected static string $table = 'faqs';
    protected static array $fillable = ['question', 'answer', 'sort_order', 'is_active'];
    protected static array $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
    protected static array $relationConfig = [];
}
