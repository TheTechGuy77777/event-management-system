<?php

namespace App\Http\Controllers;

use App\Exceptions\CheckoutException;
use App\Exceptions\PaymentException;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\PromoValidationRequest;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Services\Checkout\CheckoutService;
use App\Services\Checkout\PromoService;
use App\Services\Payment\PaymentCompletionService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private CheckoutService $checkoutService,
        private PaymentCompletionService $paymentCompletionService,
        private PromoService $promoService,
    ) {}

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

    public function store(CheckoutRequest $request, $slug)
    {
        $event = Event::with(['tickets'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $ticket = Ticket::findOrFail($request->validated('ticket_id'));

        try {
            $result = $this->checkoutService->processStore($event, $request->validated(), $ticket, $slug);
        } catch (CheckoutException $e) {
            return back()->with('error', $e->getMessage());
        }

        if ($result->isFree) {
            return redirect()->route('checkout.success', [
                'slug' => $event->slug,
                'order' => $result->order->id,
            ]);
        }

        return redirect($result->paymentData['authorization_url']);
    }

    public function callback(Request $request, $slug)
    {
        try {
            $result = $this->paymentCompletionService->complete($request->reference, $slug);

            return redirect()->route('checkout.success', [
                'slug' => $result->event->slug,
                'order' => $result->order->id,
            ]);
        } catch (PaymentException $e) {
            return redirect()->route('checkout', $slug)->with('error', $e->getMessage());
        }
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

    public function validatePromo(PromoValidationRequest $request)
    {
        $result = $this->promoService->validate(
            $request->validated('code'),
            $request->validated('event_id'),
            (float) $request->validated('amount')
        );

        if (! $result['valid']) {
            return response()->json([
                'valid' => false,
                'message' => $result['message'],
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => $result['message'],
            'discount_type' => $result['promo']->discount_type,
            'discount_value' => $result['promo']->discount_value,
            'discount_amount' => $result['discount'],
            'new_total' => $result['new_total'],
        ]);
    }
}
