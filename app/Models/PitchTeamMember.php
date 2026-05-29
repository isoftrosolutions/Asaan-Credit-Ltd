<?php

namespace App\Models;

use App\Core\Database;

class PitchTeamMember extends Model
{
    protected static string $table = 'pitch_team_members';
    protected static array $fillable = ['pitch_id', 'name', 'role', 'linkedin_url'];
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
