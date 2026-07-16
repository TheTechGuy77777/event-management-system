<?php

namespace App\Http\Controllers\EventManager;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventManager\StorePromoCodeRequest;
use App\Models\Event;
use App\Models\PromoCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

    public function store(StorePromoCodeRequest $request)
    {
        $event = Event::where('id', $request->validated('event_id'))
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($request->discount_type === 'percentage' && $request->discount_value > 100) {
            throw ValidationException::withMessages([
                'discount_value' => 'Percentage discount cannot exceed 100%.',
            ]);
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
