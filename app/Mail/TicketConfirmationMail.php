<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Order;
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
            to: $this->order->buyer_email,
            subject: 'Your Tickets for '.$this->event->name.' 🎉',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ticket-confirmation',
        );
    }
}
