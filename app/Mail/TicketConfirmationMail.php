<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public Event $event,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Tickets for ' . $this->event->name . ' 🎉',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ticket-confirmation',
        );
    }
}
