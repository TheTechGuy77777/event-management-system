<?php

namespace App\Services\Checkout;

use App\Models\Event;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        private TicketService $ticketService,
        private CheckoutNotifier $notifier,
    ) {}

    public function createPendingOrder(Event $event, array $data, ?PromoCode $promo = null): Order
    {
        $reference = 'EVT-'.strtoupper(Str::random(12));

        $order = Order::create([
            'event_id' => $event->id,
            'buyer_name' => $data['buyer_name'],
            'buyer_email' => $data['buyer_email'],
            'buyer_phone' => $data['buyer_phone'] ?? null,
            'total_amount' => $data['total_amount'],
            'platform_commission' => $data['platform_commission'],
            'manager_earnings' => $data['manager_earnings'],
            'payment_reference' => $reference,
            'payment_gateway' => $data['payment_gateway'],
            'payment_status' => 'pending',
        ]);

        if ($promo) {
            $promo->increment('usage_count');
        }

        return $order;
    }

    public function completeOrder(Order $order, Event $event, Ticket $ticket, int $quantity, array $attendees, string $buyerName, string $buyerEmail): void
    {
        if ($order->isCompleted()) {
            return;
        }

        DB::transaction(function () use ($order, $ticket, $quantity, $attendees, $buyerName, $buyerEmail, $event) {
            $this->ticketService->createOrderItems($order, $ticket, $quantity, $attendees, $buyerName, $buyerEmail);
            $this->ticketService->incrementTicketQuantity($ticket, $quantity);
            $order->update(['payment_status' => 'completed']);

            DB::afterCommit(function () use ($order, $event, $quantity) {
                $this->notifier->sendTicketConfirmation($order, $event);
                $this->notifier->notifyEventManager($order, $event, $quantity);
            });
        });
    }
}
