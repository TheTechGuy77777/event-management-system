<?php

namespace App\Services\Checkout;

class CheckoutStoreResult
{
    public function __construct(
        public Order $order,
        public Event $event,
        public Ticket $ticket,
        public array $validated,
        public bool $isFree,
        public ?array $paymentData = null,
    ) {}
}
