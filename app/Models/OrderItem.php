<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'ticket_id',
        'attendee_name',
        'attendee_email',
        'ticket_code',
        'qr_code',
        'unit_price',
        'is_checked_in',
        'checked_in_at',
    ];

    protected $casts = [
        'is_checked_in' => 'boolean',
        'checked_in_at' => 'datetime',
        'unit_price' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Scopes
    public function scopeCompletedForEvent(Builder $query, int $eventId): Builder
    {
        return $query->whereHas('order', function ($q) use ($eventId) {
            $q->where('event_id', $eventId)
                ->where('payment_status', 'completed');
        });
    }

    public function scopeCompletedForManager(Builder $query, int $userId): Builder
    {
        return $query->whereHas('order', function ($q) use ($userId) {
            $q->whereHas('event', function ($q2) use ($userId) {
                $q2->where('user_id', $userId);
            })->where('payment_status', 'completed');
        });
    }

    public function scopeCheckedIn(Builder $query): Builder
    {
        return $query->where('is_checked_in', true);
    }
}
