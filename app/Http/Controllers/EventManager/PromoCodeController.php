<?php

namespace App\Http\Controllers\EventManager;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::where('user_id', Auth::id())
            ->with('event')
            ->latest()
            ->paginate(15);

        $events = Event::where('user_id', Auth::id())
            ->whereIn('status', ['draft', 'published'])
            ->get();

        return view('eventmanager.promo-codes', compact('promoCodes', 'events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'code' => ['nullable', 'string', 'max:20', 'unique:promo_codes'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'numeric', 'min:1'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date', 'after:today'],
        ]);

        // Verify event ownership
        $event = Event::where('id', $request->event_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Validate percentage max
        if ($request->discount_type === 'percentage' && $request->discount_value > 100) {
            return back()->with('error', 'Percentage discount cannot exceed 100%.');
        }

        PromoCode::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'code' => strtoupper($request->code ?: Str::random(8)),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'usage_limit' => $request->usage_limit,
            'usage_count' => 0,
            'expires_at' => $request->expires_at,
            'is_active' => true,
        ]);

        return back()->with('success', 'Promo code created successfully!');
    }

    public function toggle(PromoCode $promoCode)
    {
        if ($promoCode->user_id !== Auth::id()) {
            abort(403);
        }

        $promoCode->update(['is_active' => ! $promoCode->is_active]);

        return back()->with('success', 'Promo code '.($promoCode->is_active ? 'deactivated' : 'activated').'.');
    }

    public function destroy(PromoCode $promoCode)
    {
        if ($promoCode->user_id !== Auth::id()) {
            abort(403);
        }

        $promoCode->delete();

        return back()->with('success', 'Promo code deleted.');
    }
}
