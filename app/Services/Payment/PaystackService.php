<?php

namespace App\Services\Payment;

use App\Exceptions\PaymentException;
use App\Models\Event;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    public function initializeTransaction(Order $order, Event $event, string $email, float $amount, string $reference, string $slug): array
    {
        try {
            $response = Http::withToken(config('services.paystack.secret_key'))
                ->post(config('services.paystack.payment_url').'/transaction/initialize', [
                    'email' => $email,
                    'amount' => $amount * 100,
                    'reference' => $reference,
                    'callback_url' => route('checkout.callback', ['slug' => $slug]),
                    'metadata' => [
                        'order_id' => $order->id,
                        'event_name' => $event->name,
                        'cancel_action' => route('checkout', ['slug' => $slug]),
                    ],
                ]);

            if ($response->successful() && $response->json('status')) {
                return [
                    'authorization_url' => $response->json('data.authorization_url'),
                    'reference' => $reference,
                ];
            }

            Log::warning('Paystack initialization failed', [
                'order_id' => $order->id,
                'response' => $response->body(),
            ]);

            throw new PaymentException('Payment initialization failed. Please try again.');
        } catch (\Throwable $e) {
            Log::error('Paystack initialization error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            throw new PaymentException('Payment initialization failed. Please try again.', 0, $e);
        }
    }

    public function verifyTransaction(string $reference): array
    {
        try {
            $response = Http::withToken(config('services.paystack.secret_key'))
                ->get(config('services.paystack.payment_url').'/transaction/verify/'.$reference);

            if (! $response->successful() || ! $response->json('status')) {
                Log::warning('Paystack verification failed', [
                    'reference' => $reference,
                    'response' => $response->body(),
                ]);

                throw new PaymentException('Payment verification failed.');
            }

            return $response->json('data');
        } catch (\Throwable $e) {
            Log::error('Paystack verification error', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            throw new PaymentException('Payment verification failed.', 0, $e);
        }
    }
}
