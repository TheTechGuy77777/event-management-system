<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'sent_by',
        'subject',
        'message',
        'recipient_type',
        'recipient_name',
        'recipients_count',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
