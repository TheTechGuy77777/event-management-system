<?php

namespace App\Services\Checkout;

use App\Models\Ticket;
use App\Models\Order;
use App\Models\OrderItem;


class TicketService
{
    public function hasSufficientQuantity(Ticket $ticket, int $quantity): bool
    {
        $available = $ticket->quantity - $ticket->quantity_sold;

        return $available >= $quantity;
    }

    public function incrementTicketQuantity(Ticket $ticket, int $quantity): bool
    {
        if (! $this->hasSufficientQuantity($ticket, $quantity)) {
            return false;
        }

        $ticket->increment('quantity_sold', $quantity);

        return true;
    }

    public function createOrderItems(
        Order $order,
        Ticket $ticket,
        int $quantity,
        array $attendees = [],
        ?string $buyerName = null,
        ?string $buyerEmail = null
    ): void {
        for ($i = 0; $i < $quantity; $i++) {
            OrderItem::create([
                'order_id' => $order->id,
                'ticket_id' => $ticket->id,
                'ticket_code' => strtoupper('EVT-' . \Illuminate\Support\Str::random(10)),
                'attendee_name' => $attendees[$i]['name'] ?? $buyerName,
                'attendee_email' => $attendees[$i]['email'] ?? $buyerEmail,
                'quantity' => 1,
                'price' => $ticket->price,
            ]);
        }
    }
}
