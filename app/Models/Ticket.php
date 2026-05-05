<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'ticket_type',
        'admission_type',
        'group_size',
        'price',
        'quantity',
        'quantity_sold',
        'purchase_limit',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function perks()
    {
        return $this->hasMany(TicketPerk::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Helpers
    public function isSoldOut(): bool
    {
        return $this->quantity_sold >= $this->quantity;
    }

    public function remainingQuantity(): int
    {
        return $this->quantity - $this->quantity_sold;
    }
}
