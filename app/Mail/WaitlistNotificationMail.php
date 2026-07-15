<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Waitlist;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WaitlistNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Waitlist $waitlist,
        public Event $event,
        public Ticket $ticket,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎟️ A ticket is available for '.$this->event->name.'!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.waitlist-notification',
        );
    }
}
