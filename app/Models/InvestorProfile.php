<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'past_investments',
        'portfolio_companies',
        'total_capital_deployed',
        'preferred_sectors',
        'preferred_stages',
        'ticket_min',
        'ticket_max',
        'preferred_geography',
        'references',
    ];

    protected function casts(): array
    {
        return [
            'past_investments' => 'integer',
            'total_capital_deployed' => 'decimal:2',
            'preferred_sectors' => 'array',
            'preferred_stages' => 'array',
            'ticket_min' => 'decimal:2',
            'ticket_max' => 'decimal:2',
            'preferred_geography' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
