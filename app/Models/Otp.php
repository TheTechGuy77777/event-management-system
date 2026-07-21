<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Otp extends Model
{
    protected $fillable = [
        'token',
        'user_id',
        'purpose',
        'code',
        'attempts',
        'last_sent_at',
        'expires_at',
        'verified_at',
    ];

    public const PURPOSE_EMAIL_VERIFICATION = 'email_verification';

    public const PURPOSE_PASSWORD_RESET = 'password_reset';

    protected $casts = [
        'last_sent_at' => 'datetime',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
