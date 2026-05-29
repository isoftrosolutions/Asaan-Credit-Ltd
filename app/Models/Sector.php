<?php

namespace App\Models;

use App\Core\Database;

class Sector extends Model
{
    protected static string $table = 'sectors';
    protected static array $fillable = ['name', 'slug', 'is_active'];
    protected static array $casts = [
        'is_active' => 'boolean',
    ];
    protected static array $relationConfig = [
        'pitches' => ['type' => 'hasMany', 'class' => Pitch::class, 'foreignKey' => 'sector_id', 'localKey' => 'id'],
    ];

    public function pitches(): array
    {
        if (!array_key_exists('pitches', $this->relations)) {
            $this->relations['pitches'] = Pitch::where('sector_id', $this->id ?? 0)->get();
        }
        return $this->relations['pitches'];
    }
}
