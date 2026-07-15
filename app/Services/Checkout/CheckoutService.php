<?php

namespace App\Services\Checkout;

use App\Exceptions\CheckoutException;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Services\Payment\PaystackService;

class CheckoutService
{
    public function __construct(
        private OrderCalculationService $calculationService,
        private PromoService $promoService,
        private TicketService $ticketService,
        private OrderService $orderService,
        private CheckoutSessionService $sessionService,
        private PaystackService $paystackService,
    ) {}

    public function prepare(Event $event, array $validated, Ticket $ticket): PreparedOrder
    {
        $subtotal = $this->calculationService->calculateSubtotal($ticket, $validated['quantity']);

        $promo = null;
        $discount = 0.0;
        $newSubtotal = $subtotal;

        if ($validated['promo_code'] ?? null) {
            $promoResult = $this->promoService->validate(
                $validated['promo_code'],
                $event->id,
                $subtotal
            );

            if ($promoResult['valid']) {
                $promo = $promoResult['promo'];
                $discount = $promoResult['discount'];
                $newSubtotal = $promoResult['new_total'];
            }
        }

        $totals = $this->calculationService->calculateTotals($event, $newSubtotal);

        $orderData = [
            'buyer_name' => $validated['buyer_name'],
            'buyer_email' => $validated['buyer_email'],
            'buyer_phone' => $validated['buyer_phone'],
            'total_amount' => $totals['total'],
            'platform_commission' => $totals['commission'],
            'manager_earnings' => $totals['manager_earnings'],
            'payment_gateway' => $validated['gateway'],
        ];

        $isFree = $ticket->ticket_type === 'free' || $totals['total'] == 0;

        return new PreparedOrder(
            subtotal: $subtotal,
            promo: $promo,
            discount: $discount,
            newSubtotal: $newSubtotal,
            totals: $totals,
            orderData: $orderData,
            isFree: $isFree,
        );
    }

    public function completeFreeOrder(Order $order, Event $event, Ticket $ticket, array $validated): void
    {
        $this->orderService->completeOrder(
            $order,
            $event,
            $ticket,
            $validated['quantity'],
            $validated['attendees'] ?? [],
            $validated['buyer_name'],
            $validated['buyer_email']
        );
    }

    public function processStore(Event $event, array $validated, Ticket $ticket, string $slug): CheckoutStoreResult
    {
        if ($this->ticketService->isSoldOut($ticket)) {
            throw new CheckoutException('Sorry, this ticket is sold out.');
        }

        if (! $this->ticketService->hasSufficientQuantity($ticket, $validated['quantity'])) {
            throw new CheckoutException('Not enough tickets available.');
        }

        $prepared = $this->prepare($event, $validated, $ticket);
        $order = $this->orderService->createPendingOrder($event, $prepared->orderData, $prepared->promo);

        $this->sessionService->store(
            $order->id,
            $ticket->id,
            $validated['quantity'],
            $validated['attendees'] ?? [],
            $validated['buyer_name'],
            $validated['buyer_email']
        );

        if ($prepared->isFree) {
            $this->completeFreeOrder($order, $event, $ticket, $validated);

            return new CheckoutStoreResult(
                order: $order,
                event: $event,
                ticket: $ticket,
                validated: $validated,
                isFree: true,
            );
        }

        $paymentData = $this->paystackService->initializeTransaction(
            $order,
            $event,
            $validated['buyer_email'],
            $prepared->totals['total'],
            $order->payment_reference,
            $slug
        );

        return new CheckoutStoreResult(
            order: $order,
            event: $event,
            ticket: $ticket,
            validated: $validated,
            isFree: false,
            paymentData: $paymentData,
        );
    }
}
