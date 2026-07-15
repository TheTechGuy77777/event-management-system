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

    // Status Helpers
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isEnded(): bool
    {
        return $this->status === 'ended';
    }

    // Event Mode Helpers
    public function isPhysical(): bool
    {
        return $this->event_mode === 'physical' || is_null($this->event_mode);
    }

    public function isOnline(): bool
    {
        return $this->event_mode === 'online';
    }

    public function isHybrid(): bool
    {
        return $this->event_mode === 'hybrid';
    }

    public function hasOnlineComponent(): bool
    {
        return in_array($this->event_mode, ['online', 'hybrid']);
    }

    // Platform Label
    public function getPlatformLabelAttribute(): string
    {
        return match ($this->platform) {
            'zoom' => 'Zoom Meeting',
            'zoom_webinar' => 'Zoom Webinar',
            'google_meet' => 'Google Meet',
            'microsoft_teams' => 'Microsoft Teams',
            'youtube_live' => 'YouTube Live',
            'custom' => 'Custom Link',
            default => 'Online',
        };
    }

    // Platform Icon
    public function getPlatformIconAttribute(): string
    {
        return match ($this->platform) {
            'zoom', 'zoom_webinar' => 'fa-solid fa-video',
            'google_meet' => 'fa-brands fa-google',
            'microsoft_teams' => 'fa-brands fa-microsoft',
            'youtube_live' => 'fa-brands fa-youtube',
            default => 'fa-solid fa-link',
        };
    }
}
