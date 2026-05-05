<?php

namespace App\Http\Controllers;

use App\Mail\TicketConfirmationMail;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PromoCode;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index($slug)
    {
        $event = Event::with(['tickets.perks', 'user'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $ticketId = request('ticket');
        $selectedTicket = null;

        if ($ticketId) {
            $selectedTicket = $event->tickets
                ->where('id', $ticketId)
                ->where('is_active', true)
                ->first();
        }

        return view('public.checkout', compact('event', 'selectedTicket'));
    }

    public function store(Request $request, $slug)
    {
        $event = Event::with(['tickets'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $request->validate([
            'ticket_id'   => ['required', 'exists:tickets,id'],
            'quantity'    => ['required', 'integer', 'min:1', 'max:10'],
            'buyer_name'  => ['required', 'string', 'max:255'],
            'buyer_email' => ['required', 'email'],
            'buyer_phone' => ['nullable', 'string', 'max:20'],
            'gateway'     => ['required', 'in:paystack,monnify'],
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);

        // Check availability
        if ($ticket->isSoldOut()) {
            return back()->with('error', 'Sorry, this ticket is sold out.');
        }

        if ($ticket->remainingQuantity() < $request->quantity) {
            return back()->with('error', 'Not enough tickets available.');
        }

        // Calculate amounts
        $unitPrice = $ticket->price;
        $quantity  = $request->quantity;
        $subtotal  = $unitPrice * $quantity;

        // Apply promo code if provided
        $promoDiscount = 0;
        $appliedPromo  = null;

        if ($request->promo_code) {
            $promo = PromoCode::where('code', strtoupper($request->promo_code))
                ->where('event_id', $event->id)
                ->first();

            if ($promo && $promo->isValid()) {
                $promoDiscount = $promo->calculateDiscount($subtotal);
                $subtotal      = max(0, $subtotal - $promoDiscount);
                $appliedPromo  = $promo;
            }
        }

        $commissionRate  = $event->commission_rate / 100;
        $commission      = 0;
        $totalAmount     = $subtotal;

        if ($event->payment_model === 'attendee_pays') {
            $commission  = $subtotal * $commissionRate;
            $totalAmount = $subtotal + $commission;
        } else {
            $commission = $subtotal * $commissionRate;
        }

        $managerEarnings = $subtotal - ($event->payment_model === 'manager_pays' ? $commission : 0);

        // Generate unique reference
        $reference = 'EVT-' . strtoupper(Str::random(12));

        // Create PENDING order
        $order = Order::create([
            'event_id'            => $event->id,
            'buyer_name'          => $request->buyer_name,
            'buyer_email'         => $request->buyer_email,
            'buyer_phone'         => $request->buyer_phone,
            'total_amount'        => $totalAmount,
            'platform_commission' => $commission,
            'manager_earnings'    => $managerEarnings,
            'payment_reference'   => $reference,
            'payment_gateway'     => $request->gateway,
            'payment_status'      => 'pending',
        ]);

        // Increment promo usage count
        if ($appliedPromo) {
            $appliedPromo->increment('usage_count');
        }

        // Store attendee info in session for after payment
        session([
            'checkout_order_id'   => $order->id,
            'checkout_ticket_id'  => $ticket->id,
            'checkout_quantity'   => $quantity,
            'checkout_attendees'  => $request->input('attendees', []),
            'checkout_buyer_name' => $request->buyer_name,
            'checkout_buyer_email' => $request->buyer_email,
        ]);

        // If free ticket or total is 0 — skip payment
        if ($ticket->ticket_type === 'free' || $totalAmount == 0) {
            return $this->completeOrder(
                $order,
                $event,
                $ticket,
                $quantity,
                $request->input('attendees', []),
                $request->buyer_name,
                $request->buyer_email
            );
        }

        // Initialize Paystack payment
        if ($request->gateway === 'paystack') {
            return $this->initializePaystack($order, $event, $request->buyer_email, $totalAmount, $reference, $slug);
        }

        // For Monnify — same flow for now
        return $this->initializePaystack($order, $event, $request->buyer_email, $totalAmount, $reference, $slug);
    }

    private function initializePaystack($order, $event, $email, $amount, $reference, $slug)
    {
        $response = Http::withToken(config('services.paystack.secret_key'))
            ->post(config('services.paystack.payment_url') . '/transaction/initialize', [
                'email'        => $email,
                'amount'       => $amount * 100,
                'reference'    => $reference,
                'callback_url' => route('checkout.callback', ['slug' => $slug]),
                'metadata'     => [
                    'order_id'      => $order->id,
                    'event_name'    => $event->name,
                    'cancel_action' => route('checkout', ['slug' => $slug]),
                ],
            ]);

        if ($response->successful() && $response->json('status')) {
            $authorizationUrl = $response->json('data.authorization_url');
            return redirect($authorizationUrl);
        }

        $order->update(['payment_status' => 'failed']);
        return back()->with('error', 'Payment initialization failed. Please try again.');
    }

    public function callback(Request $request, $slug)
    {
        $reference = $request->reference;

        if (!$reference) {
            return redirect()->route('checkout', $slug)->with('error', 'Invalid payment reference.');
        }

        $response = Http::withToken(config('services.paystack.secret_key'))
            ->get(config('services.paystack.payment_url') . '/transaction/verify/' . $reference);

        if (!$response->successful() || !$response->json('status')) {
            return redirect()->route('checkout', $slug)->with('error', 'Payment verification failed.');
        }

        $data   = $response->json('data');
        $status = $data['status'];

        $order = Order::where('payment_reference', $reference)->firstOrFail();
        $event = Event::where('slug', $slug)->firstOrFail();

        if ($status !== 'success') {
            $order->update(['payment_status' => 'failed']);
            return redirect()->route('checkout', $slug)->with('error', 'Payment was not successful. Please try again.');
        }

        $ticketId   = session('checkout_ticket_id');
        $quantity   = session('checkout_quantity');
        $attendees  = session('checkout_attendees', []);
        $buyerName  = session('checkout_buyer_name');
        $buyerEmail = session('checkout_buyer_email');

        $ticket = Ticket::findOrFail($ticketId);

        return $this->completeOrder($order, $event, $ticket, $quantity, $attendees, $buyerName, $buyerEmail);
    }

    private function completeOrder($order, $event, $ticket, $quantity, $attendees, $buyerName, $buyerEmail)
    {
        if ($order->payment_status === 'completed') {
            return redirect()->route('checkout.success', [
                'slug'  => $event->slug,
                'order' => $order->id,
            ]);
        }

        for ($i = 0; $i < $quantity; $i++) {
            $attendeeName  = $attendees[$i]['name'] ?? $buyerName;
            $attendeeEmail = $attendees[$i]['email'] ?? $buyerEmail;

            if (empty($attendeeName))  $attendeeName  = $buyerName;
            if (empty($attendeeEmail)) $attendeeEmail = $buyerEmail;

            $ticketCode = $this->generateTicketCode();

            $qrPath   = 'qrcodes/ticket-' . $ticketCode . '.svg';
            $fullPath  = storage_path('app/public/' . $qrPath);

            if (!file_exists(storage_path('app/public/qrcodes'))) {
                mkdir(storage_path('app/public/qrcodes'), 0755, true);
            }

            \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(200)
                ->errorCorrection('H')
                ->generate($ticketCode, $fullPath);

            OrderItem::create([
                'order_id'      => $order->id,
                'ticket_id'     => $ticket->id,
                'attendee_name' => $attendeeName,
                'attendee_email' => $attendeeEmail,
                'ticket_code'   => $ticketCode,
                'unit_price'    => $ticket->price,
                'is_checked_in' => false,
                'qr_code'       => $qrPath,
            ]);
        }

        $ticket->increment('quantity_sold', $quantity);
        $order->update(['payment_status' => 'completed']);

        $order->load(['items.ticket']);
        Mail::to($order->buyer_email)->send(
            new TicketConfirmationMail($order, $event)
        );

        Notification::create([
            'user_id' => $event->user_id,
            'title'   => 'New Ticket Sale! 🎉',
            'message' => $order->buyer_name . ' just bought ' . $quantity .
                ' ticket(s) for ' . $event->name . '. Total: ₦' .
                number_format($order->total_amount),
            'type'    => 'success',
        ]);

        session()->forget([
            'checkout_order_id',
            'checkout_ticket_id',
            'checkout_quantity',
            'checkout_attendees',
            'checkout_buyer_name',
            'checkout_buyer_email',
        ]);

        return redirect()->route('checkout.success', [
            'slug'  => $event->slug,
            'order' => $order->id,
        ]);
    }

    public function success($slug, Request $request)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $order = Order::with(['items.ticket'])
            ->where('id', $request->order)
            ->where('event_id', $event->id)
            ->firstOrFail();

        return view('public.checkout-success', compact('event', 'order'));
    }

    private function generateTicketCode(): string
    {
        do {
            $code = 'EVT-' . strtoupper(Str::random(6));
        } while (OrderItem::where('ticket_code', $code)->exists());

        return $code;
    }

    public function validatePromo(Request $request)
    {
        $request->validate([
            'code'     => ['required', 'string'],
            'event_id' => ['required', 'exists:events,id'],
            'amount'   => ['required', 'numeric'],
        ]);

        $promo = PromoCode::where('code', strtoupper($request->code))
            ->where('event_id', $request->event_id)
            ->first();

        if (!$promo) {
            return response()->json([
                'valid'   => false,
                'message' => 'Invalid promo code.',
            ]);
        }

        if (!$promo->isValid()) {
            return response()->json([
                'valid'   => false,
                'message' => 'This promo code has expired or reached its usage limit.',
            ]);
        }

        $discount = $promo->calculateDiscount($request->amount);

        return response()->json([
            'valid'           => true,
            'message'         => 'Promo code applied successfully!',
            'discount_type'   => $promo->discount_type,
            'discount_value'  => $promo->discount_value,
            'discount_amount' => $discount,
            'new_total'       => max(0, $request->amount - $discount),
        ]);
    }
}
