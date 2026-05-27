<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'account_type', 'phone',
        'province', 'district', 'profile_photo', 'company_name', 'bio',
        'linkedin_url', 'website_url', 'verification_status', 'verified_at',
        'is_admin', 'is_suspended', 'daily_request_count', 'daily_request_date',
        'email_verified_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_suspended' => 'boolean',
            'daily_request_date' => 'date',
        ];
    }

    public function investorProfile()
    {
        return $this->hasOne(InvestorProfile::class);
    }

    public function pitches()
    {
        return $this->hasMany(Pitch::class);
    }

    public function verificationDocuments()
    {
        return $this->hasMany(VerificationDocument::class);
    }

    public function sentInterestRequests()
    {
        return $this->hasMany(InterestRequest::class, 'sender_id');
    }

    public function receivedInterestRequests()
    {
        return $this->hasMany(InterestRequest::class, 'receiver_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
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

    public function scopeInvestors($query)
    {
        return $query->where('role', 'investor');
    }

    public function scopeEntrepreneurs($query)
    {
        return $query->where('role', 'entrepreneur');
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopeActive($query)
    {
        return $query->where('is_suspended', false);
    }
}
