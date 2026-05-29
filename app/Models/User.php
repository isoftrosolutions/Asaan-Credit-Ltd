<?php

namespace App\Models;

use App\Core\Database;

class User extends Model
{
    protected static string $table = 'users';
    protected static array $fillable = [
        'name', 'email', 'password', 'role', 'account_type', 'phone',
        'province', 'district', 'profile_photo', 'company_name', 'bio',
        'linkedin_url', 'website_url', 'verification_status', 'verified_at',
        'is_admin', 'is_suspended', 'daily_request_count', 'daily_request_date',
        'email_verified_at',
    ];
    protected static array $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_suspended' => 'boolean',
        'daily_request_date' => 'date',
    ];
    protected static array $relationConfig = [
        'investorProfile' => ['type' => 'hasOne', 'class' => InvestorProfile::class, 'foreignKey' => 'user_id', 'localKey' => 'id'],
        'pitches' => ['type' => 'hasMany', 'class' => Pitch::class, 'foreignKey' => 'user_id', 'localKey' => 'id'],
        'verificationDocuments' => ['type' => 'hasMany', 'class' => VerificationDocument::class, 'foreignKey' => 'user_id', 'localKey' => 'id'],
        'sentInterestRequests' => ['type' => 'hasMany', 'class' => InterestRequest::class, 'foreignKey' => 'sender_id', 'localKey' => 'id'],
        'receivedInterestRequests' => ['type' => 'hasMany', 'class' => InterestRequest::class, 'foreignKey' => 'receiver_id', 'localKey' => 'id'],
        'notifications' => ['type' => 'hasMany', 'class' => Notification::class, 'foreignKey' => 'user_id', 'localKey' => 'id'],
    ];

    public function investorProfile(): ?InvestorProfile
    {
        if (!array_key_exists('investorProfile', $this->relations)) {
            $this->relations['investorProfile'] = InvestorProfile::where('user_id', $this->id ?? 0)->first();
        }
        return $this->relations['investorProfile'];
    }

    public function pitches(): array
    {
        if (!array_key_exists('pitches', $this->relations)) {
            $this->relations['pitches'] = Pitch::where('user_id', $this->id ?? 0)->get();
        }
        return $this->relations['pitches'];
    }

    public function verificationDocuments(): array
    {
        if (!array_key_exists('verificationDocuments', $this->relations)) {
            $this->relations['verificationDocuments'] = VerificationDocument::where('user_id', $this->id ?? 0)->get();
        }
        return $this->relations['verificationDocuments'];
    }

    public function sentInterestRequests(): array
    {
        if (!array_key_exists('sentInterestRequests', $this->relations)) {
            $this->relations['sentInterestRequests'] = InterestRequest::where('sender_id', $this->id ?? 0)->get();
        }
        return $this->relations['sentInterestRequests'];
    }

    public function receivedInterestRequests(): array
    {
        if (!array_key_exists('receivedInterestRequests', $this->relations)) {
            $this->relations['receivedInterestRequests'] = InterestRequest::where('receiver_id', $this->id ?? 0)->get();
        }
        return $this->relations['receivedInterestRequests'];
    }

    public function notifications(): array
    {
        if (!array_key_exists('notifications', $this->relations)) {
            $this->relations['notifications'] = Notification::where('user_id', $this->id ?? 0)->get();
        }
        return $this->relations['notifications'];
    }

    public static function investors(): QueryBuilder
    {
        return (new QueryBuilder(static::class))->where('role', 'investor');
    }

    public static function entrepreneurs(): QueryBuilder
    {
        return (new QueryBuilder(static::class))->where('role', 'entrepreneur');
    }

    public static function verified(): QueryBuilder
    {
        return (new QueryBuilder(static::class))->where('verification_status', 'verified');
    }

    public static function active(): QueryBuilder
    {
        return (new QueryBuilder(static::class))->where('is_suspended', false);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function isInvestor(): bool
    {
        return $this->role === 'investor';
    }

    public function isEntrepreneur(): bool
    {
        return $this->role === 'entrepreneur';
    }
}
