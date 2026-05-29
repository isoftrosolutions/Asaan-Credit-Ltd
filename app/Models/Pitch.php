<?php

namespace App\Models;

use App\Core\Database;

class Pitch extends Model
{
    protected static string $table = 'pitches';
    protected static array $fillable = [
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
    protected static array $casts = [
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
    protected static array $relationConfig = [
        'user' => ['type' => 'belongsTo', 'class' => User::class, 'foreignKey' => 'user_id', 'ownerKey' => 'id'],
        'sector' => ['type' => 'belongsTo', 'class' => Sector::class, 'foreignKey' => 'sector_id', 'ownerKey' => 'id'],
        'media' => ['type' => 'hasMany', 'class' => PitchMedia::class, 'foreignKey' => 'pitch_id', 'localKey' => 'id'],
        'teamMembers' => ['type' => 'hasMany', 'class' => PitchTeamMember::class, 'foreignKey' => 'pitch_id', 'localKey' => 'id'],
        'interestRequests' => ['type' => 'hasMany', 'class' => InterestRequest::class, 'foreignKey' => 'pitch_id', 'localKey' => 'id'],
    ];

    public function user(): ?User
    {
        if (!array_key_exists('user', $this->relations)) {
            $this->relations['user'] = User::find($this->user_id ?? null);
        }
        return $this->relations['user'];
    }

    public function sector(): ?Sector
    {
        if (!array_key_exists('sector', $this->relations)) {
            $this->relations['sector'] = Sector::find($this->sector_id ?? null);
        }
        return $this->relations['sector'];
    }

    public function media(): array
    {
        if (!array_key_exists('media', $this->relations)) {
            $this->relations['media'] = PitchMedia::where('pitch_id', $this->id ?? 0)->get();
        }
        return $this->relations['media'];
    }

    public function teamMembers(): array
    {
        if (!array_key_exists('teamMembers', $this->relations)) {
            $this->relations['teamMembers'] = PitchTeamMember::where('pitch_id', $this->id ?? 0)->get();
        }
        return $this->relations['teamMembers'];
    }

    public function interestRequests(): array
    {
        if (!array_key_exists('interestRequests', $this->relations)) {
            $this->relations['interestRequests'] = InterestRequest::where('pitch_id', $this->id ?? 0)->get();
        }
        return $this->relations['interestRequests'];
    }

    public function matchScore(): float
    {
        return 0.0;
    }

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
            $value = $this->$field ?? null;
            if (is_array($value) ? !empty($value) : !empty($value)) {
                $score += $weight;
            }
        }
        return min(100, $score);
    }
}
