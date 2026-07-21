<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'organization_name',
        'phone',
        'profile_photo',
        'is_active',
        'is_banned',
        'custom_commission',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verification_token_used_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_banned' => 'boolean',
            'custom_commission' => 'decimal:2',
        ];
    }

    // Relationships
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function promoCodes()
    {
        return $this->hasMany(PromoCode::class);
    }

    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'sent_by');
    }

    // Role Helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEventManager(): bool
    {
        return $this->role === 'event_manager';
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
