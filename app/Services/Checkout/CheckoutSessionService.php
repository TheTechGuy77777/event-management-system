<?php

namespace App\Services\Checkout;

use Illuminate\Support\Facades\Session;

class CheckoutSessionService
{
    private const ORDER_ID = 'checkout_order_id';

    private const TICKET_ID = 'checkout_ticket_id';

    private const QUANTITY = 'checkout_quantity';

    private const ATTENDEES = 'checkout_attendees';

    private const BUYER_NAME = 'checkout_buyer_name';

    private const BUYER_EMAIL = 'checkout_buyer_email';

    public function store(int $orderId, int $ticketId, int $quantity, array $attendees, string $buyerName, string $buyerEmail): void
    {
        Session::put([
            self::ORDER_ID => $orderId,
            self::TICKET_ID => $ticketId,
            self::QUANTITY => $quantity,
            self::ATTENDEES => $attendees,
            self::BUYER_NAME => $buyerName,
            self::BUYER_EMAIL => $buyerEmail,
        ]);
    }

    public function getOrderId(): ?int
    {
        return Session::get(self::ORDER_ID);
    }

    public function getTicketId(): ?int
    {
        return Session::get(self::TICKET_ID);
    }

    public function getQuantity(): int
    {
        return Session::get(self::QUANTITY, 1);
    }

    public function getAttendees(): array
    {
        return Session::get(self::ATTENDEES, []);
    }

    public function getBuyerName(): ?string
    {
        return Session::get(self::BUYER_NAME);
    }

    public function getBuyerEmail(): ?string
    {
        return Session::get(self::BUYER_EMAIL);
    }

    public function clear(): void
    {
        Session::forget([
            self::ORDER_ID,
            self::TICKET_ID,
            self::QUANTITY,
            self::ATTENDEES,
            self::BUYER_NAME,
            self::BUYER_EMAIL,
        ]);
    }
}
