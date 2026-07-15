<?php

namespace App\Services\Checkout;

use App\Mail\TicketConfirmationMail;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class CheckoutNotifier
{
    public function sendTicketConfirmation(Order $order, Event $event): void
    {
        Mail::to($order->buyer_email)->send(new TicketConfirmationMail($order, $event));
    }

    public function notifyEventManager(Order $order, Event $event, int $quantity): void
    {
        Notification::create([
            'user_id' => $event->user_id,
            'title' => 'New Ticket Sale! 🎉',
            'message' => $order->buyer_name.' just bought '.$quantity.
                ' ticket(s) for '.$event->name.'. Total: ₦'.
                number_format($order->total_amount),
            'type' => 'success',
        ]);
    }
}
