<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PitchTeamMember extends Model
{
    protected $fillable = ['pitch_id', 'name', 'role', 'linkedin_url'];

    public function pitch()
    {
        return $this->belongsTo(Pitch::class);
    }
}
