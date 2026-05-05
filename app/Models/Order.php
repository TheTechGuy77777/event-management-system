<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'total_amount',
        'platform_commission',
        'manager_earnings',
        'payment_reference',
        'payment_gateway',
        'payment_status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'platform_commission' => 'decimal:2',
        'manager_earnings' => 'decimal:2',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Helpers
    public function isCompleted(): bool
    {
        return $this->payment_status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }
}
