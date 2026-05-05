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
        'role',
        'organization_name',
        'phone',
        'profile_photo',
        'is_active',
        'is_banned',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_banned' => 'boolean',
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

    // Role Helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEventManager(): bool
    {
        return $this->role === 'event_manager';
    }
}
