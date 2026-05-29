<?php

namespace App\Models;

use App\Core\Database;

class PitchMedia extends Model
{
    protected static string $table = 'pitch_media';
    protected static array $fillable = ['pitch_id', 'file_path', 'file_type', 'sort_order'];
    protected static array $casts = [];
    protected static array $relationConfig = [
        'pitch' => ['type' => 'belongsTo', 'class' => Pitch::class, 'foreignKey' => 'pitch_id', 'ownerKey' => 'id'],
    ];

    public function pitch(): ?Pitch
    {
        if (!array_key_exists('pitch', $this->relations)) {
            $this->relations['pitch'] = Pitch::find($this->pitch_id ?? null);
        }
        return $this->relations['pitch'];
    }
}
