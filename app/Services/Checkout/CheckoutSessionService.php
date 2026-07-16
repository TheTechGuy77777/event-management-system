<?php

namespace App\Services\Checkout;

use Illuminate\Support\Facades\Session;

class CheckoutSessionService
{
    private const ORDER_ID = 'checkout_order_id';

    private const BUYER_EMAIL = 'checkout_buyer_email';

    public function store(int $orderId, string $buyerEmail): void
    {
        Session::put([
            self::ORDER_ID => $orderId,
            self::BUYER_EMAIL => $buyerEmail,
        ]);
    }

    public function getOrderId(): ?int
    {
        return Session::get(self::ORDER_ID);
    }

    public function getBuyerEmail(): ?string
    {
        return Session::get(self::BUYER_EMAIL);
    }

    public function clear(): void
    {
        Session::forget([
            self::ORDER_ID,
            self::BUYER_EMAIL,
            'checkout_ticket_id',
            'checkout_quantity',
            'checkout_attendees',
            'checkout_buyer_name',
        ]);
    }
}
