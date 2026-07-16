<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'event_type',
        'event_mode',
        'platform',
        'meeting_link',
        'meeting_id',
        'meeting_passcode',
        'whatsapp_link',
        'country',
        'location',
        'is_virtual',
        'virtual_link',
        'start_date',
        'end_date',
        'timezone',
        'is_recurring',
        'recurrence_rule',
        'recurrence_end',
        'cover_image',
        'payment_model',
        'commission_rate',
        'status',
        'published_at',
        'qr_code',
        'instagram',
        'twitter',
        'facebook',
        'website',
    ];

    protected $casts = [
        'is_virtual' => 'boolean',
        'is_recurring' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'published_at' => 'datetime',
        'recurrence_end' => 'date',
        'commission_rate' => 'decimal:2',
        'event_mode' => 'string',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function lineup()
    {
        return $this->hasMany(EventLineup::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function promoCodes()
    {
        return $this->hasMany(PromoCode::class);
    }

    public function waitlists()
    {
        return $this->hasMany(Waitlist::class);
    }

    // Scopes
    public function scopeForManager($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
