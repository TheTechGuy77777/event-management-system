<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Services\Checkout\CheckoutSessionService;
use App\Services\Checkout\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private CheckoutSessionService $sessionService,
    ) {}

    public function paystack(Request $request)
    {
        $paystackSecretKey = config('services.paystack.secret_key');
        $signature = $request->header('x-paystack-signature');
        $computedSignature = hash_hmac('sha512', $request->getContent(), $paystackSecretKey);

        if ($signature !== $computedSignature) {
            Log::warning('Invalid Paystack webhook signature');

            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $payload = $request->all();
        $event = $payload['event'] ?? null;

        Log::info('Paystack webhook received', ['event' => $event]);

        if ($event === 'charge.success') {
            $reference = $payload['data']['reference'] ?? null;

            if (! $reference) {
                return response()->json(['message' => 'No reference'], 400);
            }

            $order = Order::where('payment_reference', $reference)
                ->where('payment_status', 'pending')
                ->first();

            if (! $order) {
                return response()->json(['message' => 'Order not found or already processed'], 200);
            }

            $eventModel = Event::find($order->event_id);
            $ticket = Ticket::find($this->sessionService->getTicketId());

            if (! $eventModel || ! $ticket) {
                Log::error('Webhook: Event or ticket not found', ['order_id' => $order->id]);

                return response()->json(['message' => 'Event or ticket not found'], 200);
            }

            $quantity = $this->sessionService->getQuantity();
            $attendees = $this->sessionService->getAttendees();
            $buyerName = $order->buyer_name;
            $buyerEmail = $order->buyer_email;

            $this->orderService->completeOrder(
                $order,
                $eventModel,
                $ticket,
                $quantity,
                $attendees,
                $buyerName,
                $buyerEmail
            );

            Log::info('Paystack webhook: Order completed', ['order_id' => $order->id]);
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }
}
