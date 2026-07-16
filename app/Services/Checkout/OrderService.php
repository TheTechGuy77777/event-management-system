<?php

namespace App\Services\Checkout;

use App\Exceptions\CheckoutException;
use App\Models\Event;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        private TicketService $ticketService,
        private CheckoutNotifier $notifier,
    ) {}

    public function createPendingOrder(
        Event $event,
        Ticket $ticket,
        int $quantity,
        array $attendees,
        string $buyerName,
        string $buyerEmail,
        array $data,
        ?PromoCode $promo = null
    ): Order {
        $reference = 'EVT-'.strtoupper(Str::random(12));

        $order = Order::create([
            'event_id' => $event->id,
            'buyer_name' => $buyerName,
            'buyer_email' => $buyerEmail,
            'buyer_phone' => $data['buyer_phone'] ?? null,
            'total_amount' => $data['total_amount'],
            'platform_commission' => $data['platform_commission'],
            'manager_earnings' => $data['manager_earnings'],
            'payment_reference' => $reference,
            'payment_gateway' => $data['payment_gateway'],
            'payment_status' => 'pending',
            'checkout_meta' => [
                'ticket_id' => $ticket->id,
                'quantity' => $quantity,
                'attendees' => $attendees,
                'promo_code' => $promo?->code,
            ],
        ]);

        if ($promo) {
            $promo->increment('usage_count');
        }

        return $order;
    }

    public function completeOrder(Order $order, Event $event): Order
    {
        return DB::transaction(function () use ($order, $event) {
            $lockedOrder = Order::lockForUpdate()->find($order->id);

            if (! $lockedOrder) {
                throw new CheckoutException('Order not found.');
            }

            if ($lockedOrder->isCompleted()) {
                return $lockedOrder;
            }

            $meta = $lockedOrder->checkout_meta ?? [];
            $ticketId = $meta['ticket_id'] ?? null;
            $quantity = (int) ($meta['quantity'] ?? 0);
            $attendees = $meta['attendees'] ?? [];
            $promoCode = $meta['promo_code'] ?? null;

            if (! $ticketId || $quantity <= 0) {
                throw new CheckoutException('Invalid checkout metadata.');
            }

            $ticket = Ticket::lockForUpdate()->find($ticketId);

            if (! $ticket) {
                throw new CheckoutException('Ticket not found.');
            }

            if ($ticket->isSoldOut() || $ticket->remainingQuantity() < $quantity) {
                $lockedOrder->update(['payment_status' => 'failed']);

                if ($promoCode) {
                    PromoCode::where('code', $promoCode)->decrement('usage_count');
                }

                Log::error('Order completion failed: ticket sold out at completion (possible race). Manual refund required — Paystack test mode does not auto-refund.', [
                    'order_id' => $lockedOrder->id,
                    'payment_reference' => $lockedOrder->payment_reference,
                    'ticket_id' => $ticketId,
                    'requested_quantity' => $quantity,
                    'quantity_sold' => $ticket->quantity_sold,
                    'quantity' => $ticket->quantity,
                ]);

                return $lockedOrder;
            }

            if (! $this->ticketService->incrementTicketQuantity($ticket, $quantity)) {
                $lockedOrder->update(['payment_status' => 'failed']);

                if ($promoCode) {
                    PromoCode::where('code', $promoCode)->decrement('usage_count');
                }

                Log::error('Order completion failed: could not reserve ticket inventory. Manual refund required.', [
                    'order_id' => $lockedOrder->id,
                    'ticket_id' => $ticketId,
                    'requested_quantity' => $quantity,
                ]);

                return $lockedOrder;
            }

            $this->ticketService->createOrderItems(
                $lockedOrder,
                $ticket,
                $quantity,
                $attendees,
                $lockedOrder->buyer_name,
                $lockedOrder->buyer_email
            );

            $lockedOrder->update(['payment_status' => 'completed']);

            DB::afterCommit(function () use ($lockedOrder, $event, $quantity) {
                $this->notifier->sendTicketConfirmation($lockedOrder, $event);
                $this->notifier->notifyEventManager($lockedOrder, $event, $quantity);
            });

            return $lockedOrder;
        });
    }
}
