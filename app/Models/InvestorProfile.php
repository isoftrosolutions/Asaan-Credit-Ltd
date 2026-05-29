<?php

namespace App\Models;

use App\Core\Database;

class InvestorProfile extends Model
{
    protected static string $table = 'investor_profiles';
    protected static array $fillable = [
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
    protected static array $casts = [
        'past_investments' => 'integer',
        'total_capital_deployed' => 'decimal:2',
        'preferred_sectors' => 'array',
        'preferred_stages' => 'array',
        'ticket_min' => 'decimal:2',
        'ticket_max' => 'decimal:2',
        'preferred_geography' => 'array',
    ];
    protected static array $relationConfig = [
        'user' => ['type' => 'belongsTo', 'class' => User::class, 'foreignKey' => 'user_id', 'ownerKey' => 'id'],
    ];

    public function user(): ?User
    {
        if (!array_key_exists('user', $this->relations)) {
            $this->relations['user'] = User::find($this->user_id ?? null);
        }
        return $this->relations['user'];
    }

    public static function updateOrCreate(array $attributes, array $values = []): ?static
    {
        $conditions = [];
        $params = [];
        foreach ($attributes as $col => $val) {
            $conditions[] = "{$col} = ?";
            $params[] = $val;
        }
        $whereSql = implode(' AND ', $conditions);
        $existing = Database::fetch(
            "SELECT * FROM " . static::$table . " WHERE {$whereSql} LIMIT 1",
            $params
        );
        if ($existing) {
            $model = new static();
            $model->fill((array)$existing);
            $model->update($values);
            return $model;
        }
        return static::create(array_merge($attributes, $values));
    }
}
