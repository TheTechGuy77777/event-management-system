<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Checkout\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        private OrderService $orderService,
    ) {}

    public function paystack(Request $request)
    {
        $paystackSecretKey = config('services.paystack.secret_key');
        $signature = $request->header('x-paystack-signature');
        $computedSignature = hash_hmac('sha512', $request->getContent(), $paystackSecretKey);

        if (! hash_equals((string) $signature, (string) $computedSignature)) {
            Log::warning('Invalid Paystack webhook signature');

            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $payload = $request->all();
        $event = $payload['event'] ?? null;

        Log::info('Paystack webhook received', ['event' => $event]);

        if ($event === 'charge.success') {
            $reference = $payload['data']['reference'] ?? null;
            $paidAmountKobo = $payload['data']['amount'] ?? null;

            if (! $reference || ! $paidAmountKobo) {
                return response()->json(['message' => 'No reference or amount'], 400);
            }

            $order = Order::where('payment_reference', $reference)
                ->where('payment_status', 'pending')
                ->with(['items.ticket', 'event.user'])
                ->first();

            if (! $order) {
                return response()->json(['message' => 'Order not found or already processed'], 200);
            }

            $eventModel = $order->event;

            if (! $eventModel) {
                Log::error('Webhook: Event not found', ['order_id' => $order->id]);

                return response()->json(['message' => 'Event not found'], 200);
            }

            $paidAmount = (float) $paidAmountKobo / 100;
            $expectedAmount = (float) $order->total_amount;

            if (round($paidAmount, 2) !== round($expectedAmount, 2)) {
                Log::warning('Webhook amount mismatch', [
                    'order_id' => $order->id,
                    'expected' => $expectedAmount,
                    'paid' => $paidAmount,
                    'reference' => $reference,
                ]);

                $order->update(['payment_status' => 'failed']);

                return response()->json(['message' => 'Amount mismatch'], 200);
            }

            $completed = $this->orderService->completeOrder($order, $eventModel);

            if ($completed->isCompleted()) {
                Log::info('Paystack webhook: Order completed', ['order_id' => $completed->id]);
            } else {
                Log::warning('Paystack webhook: Order not completed', [
                    'order_id' => $completed->id,
                    'status' => $completed->payment_status,
                ]);
            }
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }
}
