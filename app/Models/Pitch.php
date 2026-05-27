<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pitch extends Model
{
    protected $fillable = [
        'user_id', 'tagline', 'company_registration_number', 'company_type',
        'short_summary', 'problem_statement', 'solution',
        'market_size', 'target_customers', 'competitors', 'competitive_advantage',
        'business_model', 'revenue_model', 'traction',
        'monthly_revenue', 'monthly_users', 'growth_rate', 'customer_retention',
        'funding_amount', 'minimum_investment', 'previous_funding',
        'previous_funding_source', 'equity_offered', 'fund_usage', 'valuation',
        'pitch_deck', 'pitch_video_url', 'stage', 'product_stage',
        'sector_id', 'is_active', 'is_hidden', 'is_featured',
        'has_legal_disputes', 'legal_details', 'existing_debt',
        'business_type', 'customer_type', 'looking_for',
        'investor_involvement', 'open_to_acquisition',
        'monthly_burn', 'runway_months', 'matchmaking_tags',
        'relocate_willingness', 'financial_projections',
        'completeness_score', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'funding_amount' => 'decimal:2',
            'minimum_investment' => 'decimal:2',
            'previous_funding' => 'decimal:2',
            'monthly_revenue' => 'decimal:2',
            'monthly_burn' => 'decimal:2',
            'equity_offered' => 'decimal:2',
            'valuation' => 'decimal:2',
            'growth_rate' => 'decimal:2',
            'customer_retention' => 'decimal:2',
            'is_active' => 'boolean',
            'is_hidden' => 'boolean',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'has_legal_disputes' => 'boolean',
            'open_to_acquisition' => 'boolean',
            'matchmaking_tags' => 'array',
            'completeness_score' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function media()
    {
        return $this->hasMany(PitchMedia::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(PitchTeamMember::class);
    }

    public function interestRequests()
    {
        return $this->hasMany(InterestRequest::class);
    }

    public function matchScore(): float
    {
        return 0;
    }

    /**
     * Returns 0-100 score based on field presence. Used as an anti-spam signal
     * and to surface "ready to publish" pitches on the homepage.
     */
    public function computeCompleteness(): int
    {
        $weights = [
            'tagline' => 5, 'short_summary' => 5, 'sector_id' => 3,
            'stage' => 3, 'product_stage' => 3, 'company_type' => 2,
            'problem_statement' => 8, 'solution' => 8, 'market_size' => 4,
            'target_customers' => 4, 'competitors' => 3, 'competitive_advantage' => 4,
            'business_model' => 5, 'revenue_model' => 3, 'traction' => 5,
            'funding_amount' => 6, 'equity_offered' => 4, 'fund_usage' => 4,
            'valuation' => 3, 'minimum_investment' => 2,
            'pitch_deck' => 6, 'pitch_video_url' => 3,
            'looking_for' => 2, 'investor_involvement' => 2,
            'matchmaking_tags' => 3,
        ];
        $score = 0;
        foreach ($weights as $field => $weight) {
            $value = $this->{$field};
            if (is_array($value) ? !empty($value) : !blank($value)) {
                $score += $weight;
            }
        }
        return min(100, $score);
    }
}
