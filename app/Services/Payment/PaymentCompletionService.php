<?php

namespace App\Services\Payment;

use App\Exceptions\PaymentException;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Services\Checkout\CheckoutSessionService;
use App\Services\Checkout\OrderService;

class PaymentCompletionService
{
    public function __construct(
        private PaystackService $paystackService,
        private OrderService $orderService,
        private CheckoutSessionService $sessionService,
    ) {}

    public function complete(string $reference, string $slug): PaymentCompletionResult
    {
        if (! $reference) {
            throw new PaymentException('Invalid payment reference.');
        }

        $verification = $this->paystackService->verifyTransaction($reference);

        if (($verification['status'] ?? null) !== 'success') {
            $order = Order::where('payment_reference', $reference)->first();
            if ($order) {
                $order->update(['payment_status' => 'failed']);
            }

            throw new PaymentException('Payment was not successful. Please try again.');
        }

        $order = Order::where('payment_reference', $reference)->firstOrFail();
        $event = Event::where('slug', $slug)->firstOrFail();

        $ticketId = $this->sessionService->getTicketId();
        $quantity = $this->sessionService->getQuantity();
        $attendees = $this->sessionService->getAttendees();
        $buyerName = $this->sessionService->getBuyerName() ?? $order->buyer_name;
        $buyerEmail = $this->sessionService->getBuyerEmail() ?? $order->buyer_email;

        $ticket = Ticket::findOrFail($ticketId);

        $this->orderService->completeOrder(
            $order,
            $event,
            $ticket,
            $quantity,
            $attendees,
            $buyerName,
            $buyerEmail
        );

        $this->sessionService->clear();

        return new PaymentCompletionResult(
            order: $order,
            event: $event,
            ticket: $ticket,
            quantity: $quantity,
            attendees: $attendees,
            buyerName: $buyerName,
            buyerEmail: $buyerEmail,
        );
    }
}
