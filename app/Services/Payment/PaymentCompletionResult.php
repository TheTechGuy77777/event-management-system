<?php

namespace App\Services\Payment;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;

class PaymentCompletionResult
{
    public function __construct(
        public Order $order,
        public Event $event,
        public ?Ticket $ticket,
        public int $quantity,
        public array $attendees,
        public string $buyerName,
        public string $buyerEmail,
    ) {}
}
