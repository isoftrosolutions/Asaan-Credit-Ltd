<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PitchMedia extends Model
{
    protected $fillable = ['pitch_id', 'file_path', 'file_type', 'sort_order'];

    public function pitch()
    {
        return $this->belongsTo(Pitch::class);
    }
}
