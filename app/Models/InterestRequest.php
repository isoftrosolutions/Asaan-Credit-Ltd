<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterestRequest extends Model
{
    protected $fillable = [
        'sender_id', 'receiver_id', 'pitch_id',
        'message', 'status', 'responded_at', 'rejected_until',
    ];

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
            'rejected_until' => 'datetime',
        ];
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function pitch()
    {
        return $this->belongsTo(Pitch::class);
    }
}
