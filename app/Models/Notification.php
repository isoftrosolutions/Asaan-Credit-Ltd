<?php

namespace App\Models;

use App\Core\Database;

class Notification extends Model
{
    protected static string $table = 'notifications';
    protected static array $fillable = [
        'user_id', 'type', 'title', 'body', 'action_url', 'is_read',
    ];
    protected static array $casts = [
        'is_read' => 'boolean',
    ];
    protected static array $relationConfig = [
        'user' => ['type' => 'belongsTo', 'class' => User::class, 'foreignKey' => 'user_id', 'ownerKey' => 'id'],
    ];

    public function user(): ?User
    {
        if (!array_key_exists('user', $this->relations)) {
            $this->relations['user'] = User::find($this->user_id ?? null);
        }
        return $this->relations['user'];
    }
}
