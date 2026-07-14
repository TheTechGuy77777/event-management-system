<?php

namespace App\Http\Controllers;

use App\Mail\TicketConfirmationMail;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    public function paystack(Request $request)
    {
        // Verify webhook signature
        $paystackSecretKey = config('services.paystack.secret_key');
        $signature = $request->header('x-paystack-signature');
        $computedSignature = hash_hmac('sha512', $request->getContent(), $paystackSecretKey);

        if ($signature !== $computedSignature) {
            Log::warning('Invalid Paystack webhook signature');
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $payload = $request->all();
        $event   = $payload['event'] ?? null;
        $data    = $payload['data'] ?? [];

        Log::info('Paystack webhook received', ['event' => $event]);

        if ($event === 'charge.success') {
            $reference = $data['reference'] ?? null;

            if (!$reference) {
                return response()->json(['message' => 'No reference'], 400);
            }

            $order = Order::where('payment_reference', $reference)
                ->where('payment_status', 'pending')
                ->first();

            if (!$order) {
                // Already processed or not found
                return response()->json(['message' => 'Order not found or already processed'], 200);
            }

            $eventModel = Event::find($order->event_id);
            $ticket     = Ticket::find(session('checkout_ticket_id'));

            if (!$eventModel || !$ticket) {
                Log::error('Webhook: Event or ticket not found', ['order_id' => $order->id]);
                return response()->json(['message' => 'Event or ticket not found'], 200);
            }

            // Complete the order
            $quantity   = session('checkout_quantity', 1);
            $attendees  = session('checkout_attendees', []);
            $buyerName  = $order->buyer_name;
            $buyerEmail = $order->buyer_email;

            // Create order items
            for ($i = 0; $i < $quantity; $i++) {
                $attendeeName  = $attendees[$i]['name'] ?? $buyerName;
                $attendeeEmail = $attendees[$i]['email'] ?? $buyerEmail;

                $ticketCode = $this->generateTicketCode();
                $qrPath     = 'qrcodes/ticket-' . $ticketCode . '.svg';
                $fullPath   = storage_path('app/public/' . $qrPath);

                if (!file_exists(storage_path('app/public/qrcodes'))) {
                    mkdir(storage_path('app/public/qrcodes'), 0755, true);
                }

                \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                    ->size(200)
                    ->errorCorrection('H')
                    ->generate($ticketCode, $fullPath);

                OrderItem::create([
                    'order_id'       => $order->id,
                    'ticket_id'      => $ticket->id,
                    'attendee_name'  => $attendeeName,
                    'attendee_email' => $attendeeEmail,
                    'ticket_code'    => $ticketCode,
                    'unit_price'     => $ticket->price,
                    'is_checked_in'  => false,
                    'qr_code'        => $qrPath,
                ]);
            }

            $ticket->increment('quantity_sold', $quantity);
            $order->update(['payment_status' => 'completed']);

            $order->load(['items.ticket']);
            Mail::to($order->buyer_email)->send(
                new TicketConfirmationMail($order, $eventModel)
            );

            Notification::create([
                'user_id' => $eventModel->user_id,
                'title'   => 'New Ticket Sale! 🎉',
                'message' => $order->buyer_name . ' just bought ' . $quantity .
                    ' ticket(s) for ' . $eventModel->name . '. Total: ₦' .
                    number_format($order->total_amount),
                'type'    => 'success',
            ]);

            Log::info('Paystack webhook: Order completed', ['order_id' => $order->id]);
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }

    private function generateTicketCode(): string
    {
        do {
            $code = 'EVT-' . strtoupper(Str::random(6));
        } while (OrderItem::where('ticket_code', $code)->exists());

        return $code;
    }
}
