<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventLineup extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'role',
        'photo',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
