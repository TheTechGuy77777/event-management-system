<?php

namespace App\Services\Payment;

use App\Exceptions\CheckoutException;
use App\Exceptions\PaymentException;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Services\Checkout\OrderService;
use Illuminate\Support\Facades\Log;

class PaymentCompletionService
{
    public function __construct(
        private PaystackService $paystackService,
        private OrderService $orderService,
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

        $order = Order::where('payment_reference', $reference)
            ->with(['items.ticket'])
            ->firstOrFail();
        $event = Event::where('slug', $slug)->firstOrFail();

        if ($order->event_id !== $event->id) {
            Log::warning('Payment callback: order-event mismatch', [
                'order_id' => $order->id,
                'event_id' => $order->event_id,
                'slug' => $slug,
            ]);

            throw new PaymentException('Invalid payment callback.');
        }

        $paidAmount = round(($verification['amount'] ?? 0) / 100, 2);

        if ($paidAmount !== (float) $order->total_amount) {
            Log::warning('Payment amount mismatch', [
                'order_id' => $order->id,
                'expected' => $order->total_amount,
                'paid' => $paidAmount,
            ]);

            throw new PaymentException('Payment amount mismatch. Please contact support.');
        }

        // Webhook is the source of truth; the callback just completes the
        // order if it hasn't been completed yet (idempotent under lock).
        try {
            $completedOrder = $this->orderService->completeOrder($order, $event);
        } catch (CheckoutException $e) {
            throw new PaymentException($e->getMessage());
        }

        if ($completedOrder->isFailed()) {
            throw new PaymentException('Sorry, this ticket is no longer available. Your payment will be refunded.');
        }

        $meta = $completedOrder->checkout_meta ?? [];
        $ticket = Ticket::find($meta['ticket_id'] ?? null);
        $quantity = (int) ($meta['quantity'] ?? $completedOrder->items()->count());
        $attendees = $meta['attendees'] ?? $completedOrder->items->map(fn ($item) => [
            'name' => $item->attendee_name,
            'email' => $item->attendee_email,
        ])->toArray();

        return new PaymentCompletionResult(
            order: $completedOrder,
            event: $event,
            ticket: $ticket,
            quantity: $quantity,
            attendees: $attendees,
            buyerName: $completedOrder->buyer_name,
            buyerEmail: $completedOrder->buyer_email,
        );
    }
}
