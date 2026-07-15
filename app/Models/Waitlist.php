<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'event_id',
        'name',
        'email',
        'is_notified',
        'priority_expires_at',
    ];

    protected $casts = [
        'is_notified' => 'boolean',
        'priority_expires_at' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function hasPriorityWindow(): bool
    {
        return $this->priority_expires_at && $this->priority_expires_at->isFuture();
    }
}
