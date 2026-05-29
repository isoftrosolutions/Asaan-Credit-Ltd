<?php

namespace App\Models;

use App\Core\Database;

class InterestRequest extends Model
{
    protected static string $table = 'interest_requests';
    protected static array $fillable = [
        'sender_id', 'receiver_id', 'pitch_id',
        'message', 'status', 'responded_at', 'rejected_until',
    ];
    protected static array $casts = [
        'responded_at' => 'datetime',
        'rejected_until' => 'datetime',
    ];
    protected static array $relationConfig = [
        'sender' => ['type' => 'belongsTo', 'class' => User::class, 'foreignKey' => 'sender_id', 'ownerKey' => 'id'],
        'receiver' => ['type' => 'belongsTo', 'class' => User::class, 'foreignKey' => 'receiver_id', 'ownerKey' => 'id'],
        'pitch' => ['type' => 'belongsTo', 'class' => Pitch::class, 'foreignKey' => 'pitch_id', 'ownerKey' => 'id'],
    ];

    public function sender(): ?User
    {
        if (!array_key_exists('sender', $this->relations)) {
            $this->relations['sender'] = User::find($this->sender_id ?? null);
        }
        return $this->relations['sender'];
    }

    public function receiver(): ?User
    {
        if (!array_key_exists('receiver', $this->relations)) {
            $this->relations['receiver'] = User::find($this->receiver_id ?? null);
        }
        return $this->relations['receiver'];
    }

    public function pitch(): ?Pitch
    {
        if (!array_key_exists('pitch', $this->relations)) {
            $this->relations['pitch'] = Pitch::find($this->pitch_id ?? null);
        }
        return $this->relations['pitch'];
    }
}
