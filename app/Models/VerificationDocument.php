<?php

namespace App\Models;

use App\Core\Database;

class VerificationDocument extends Model
{
    protected static string $table = 'verification_documents';
    protected static array $fillable = [
        'user_id', 'document_type', 'file_path',
        'status', 'rejection_reason', 'reviewed_by', 'reviewed_at',
    ];
    protected static array $casts = [];
    protected static array $relationConfig = [
        'user' => ['type' => 'belongsTo', 'class' => User::class, 'foreignKey' => 'user_id', 'ownerKey' => 'id'],
        'reviewer' => ['type' => 'belongsTo', 'class' => User::class, 'foreignKey' => 'reviewed_by', 'ownerKey' => 'id'],
    ];

    public function user(): ?User
    {
        if (!array_key_exists('user', $this->relations)) {
            $this->relations['user'] = User::find($this->user_id ?? null);
        }
        return $this->relations['user'];
    }

    public function reviewer(): ?User
    {
        if (!array_key_exists('reviewer', $this->relations)) {
            $this->relations['reviewer'] = User::find($this->reviewed_by ?? null);
        }
        return $this->relations['reviewer'];
    }
}
