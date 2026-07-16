<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'gateway',
        'reference',
        'status',
        'amount',
        'gateway_fee',
        'platform_commission',
        'manager_earnings',
        'payload',
        'metadata',
        'completed_at',
        'refunded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_fee' => 'decimal:2',
        'platform_commission' => 'decimal:2',
        'manager_earnings' => 'decimal:2',
        'payload' => 'array',
        'metadata' => 'array',
        'completed_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
