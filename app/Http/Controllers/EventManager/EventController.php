<?php

namespace App\Http\Controllers\EventManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $query = Event::where('user_id', Auth::id())
            ->with(['tickets', 'orders', 'category']);

        if (request('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }

        $events = $query->latest()->paginate(10);

        return view('eventmanager.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('eventmanager.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:75'],
            'description'   => ['required', 'string'],
            'event_type'    => ['required', 'string'],
            'category_id'   => ['required', 'exists:categories,id'],
            'start_date'    => ['required', 'date'],
            'end_date'      => ['required', 'date', 'after:start_date'],
            'payment_model' => ['required', 'in:attendee_pays,manager_pays'],
            'cover_image'   => ['nullable', 'image', 'mimes:jpeg,png', 'max:2048'],
        ]);

        // Generate unique slug
        $slug = $request->slug
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        // Make slug unique
        $originalSlug = $slug;
        $count = 1;
        while (Event::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Handle cover image
        $coverImage = null;
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image')->store('events', 'public');
        }

        // Create event
        $event = Event::create([
            'user_id'          => auth::id(),
            'category_id'      => $request->category_id,
            'name'             => $request->name,
            'slug'             => $slug,
            'description'      => $request->description,
            'event_type'       => $request->event_type,
            'country'          => $request->country,
            'location'         => $request->location,
            'is_virtual'       => $request->is_virtual == '1',
            'virtual_link'     => $request->virtual_link,
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'timezone'         => $request->timezone,
            'is_recurring'     => $request->is_recurring == '1',
            'recurrence_rule'  => $request->recurrence_rule,
            'recurrence_end'   => $request->recurrence_end,
            'cover_image'      => $coverImage,
            'payment_model'    => $request->payment_model,
            'commission_rate'  => 0,
            'status'           => 'draft',
            'instagram'        => $request->instagram,
            'twitter'          => $request->twitter,
            'facebook'         => $request->facebook,
            'website'          => $request->website,
        ]);

        // Create tickets
        if ($request->tickets) {
            foreach ($request->tickets as $ticketData) {
                if (empty($ticketData['name'])) continue;

                $ticket = $event->tickets()->create([
                    'name'           => $ticketData['name'],
                    'ticket_type'    => $ticketData['ticket_type'] ?? 'paid',
                    'admission_type' => $ticketData['admission_type'] ?? 'single',
                    'group_size'     => $ticketData['group_size'] ?? null,
                    'price'          => $ticketData['price'] ?? 0,
                    'quantity'       => $ticketData['quantity'] ?? 0,
                    'purchase_limit' => $ticketData['purchase_limit'] ?? 1,
                    'description'    => $ticketData['description'] ?? null,
                    'is_active'      => true,
                ]);

                // Create perks
                if (!empty($ticketData['perks'])) {
                    foreach ($ticketData['perks'] as $perk) {
                        if (!empty($perk)) {
                            $ticket->perks()->create(['perk' => $perk]);
                        }
                    }
                }
            }
        }

        // Create lineup
        if ($request->lineup) {
            foreach ($request->lineup as $member) {
                if (empty($member['name'])) continue;
                $event->lineup()->create([
                    'name' => $member['name'],
                    'role' => $member['role'] ?? '',
                ]);
            }
        }

        // Check if publish was requested
        if ($request->action === 'publish') {
            // Must have at least one ticket
            if ($event->tickets->isEmpty()) {
                return redirect()->route('dashboard.events.index')
                    ->with('error', 'Event saved as draft. Add at least one ticket before publishing.');
            }

            // Generate QR code
            $qrPath = 'qrcodes/event-' . $event->id . '.svg';
            $fullPath = storage_path('app/public/' . $qrPath);

            if (!file_exists(storage_path('app/public/qrcodes'))) {
                mkdir(storage_path('app/public/qrcodes'), 0755, true);
            }

            \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate(url('/events/' . $event->slug), $fullPath);

            $event->update([
                'status'       => 'published',
                'published_at' => now(),
                'qr_code'      => $qrPath,
            ]);

            return redirect()->route('dashboard.events.index')
                ->with('success', 'Your event is now live! 🎉');
        }

        return redirect()->route('dashboard.events.index')
            ->with('success', 'Event created successfully as a draft!');
    }

    public function publish(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        if ($event->tickets->isEmpty()) {
            return back()->with('error', 'You must add at least one ticket before publishing.');
        }

        $commissionRate = 0;

        // Generate QR code and save it
        $qrPath = 'qrcodes/event-' . $event->id . '.svg';
        $fullPath = storage_path('app/public/' . $qrPath);

        if (!file_exists(storage_path('app/public/qrcodes'))) {
            mkdir(storage_path('app/public/qrcodes'), 0755, true);
        }

        \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate(url('/events/' . $event->slug), $fullPath);

        $event->update([
            'status'          => 'published',
            'published_at'    => now(),
            'commission_rate' => $commissionRate,
            'qr_code'         => $qrPath,
        ]);

        return redirect()->route('dashboard.events.index')
            ->with('success', 'Your event is now live! 🎉');
    }

    public function destroy(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        // Get all attendees who bought tickets
        $orders = \App\Models\Order::where('event_id', $event->id)
            ->where('payment_status', 'completed')
            ->with('items')
            ->get();

        // Cancel the event
        $event->update(['status' => 'cancelled']);

        // Notify all attendees by email
        foreach ($orders as $order) {
            \Illuminate\Support\Facades\Mail::to($order->buyer_email)
                ->send(new \App\Mail\EventCancelledMail($event, $order));
            sleep(1); // avoid Mailtrap rate limit
        }

        // Soft delete
        $event->delete();

        return redirect()->route('dashboard.events.index')
            ->with('success', 'Event cancelled. All attendees have been notified.');
    }

    public function edit(Event $event)
    {
        // Verify ownership
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        // Only draft events can be edited
        if ($event->status !== 'draft') {
            return redirect()->route('dashboard.events.index')
                ->with('error', 'Only draft events can be edited.');
        }

        $categories = Category::where('is_active', true)->get();

        return view('eventmanager.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        // Verify ownership
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        // Only draft events can be edited
        if ($event->status !== 'draft') {
            return redirect()->route('dashboard.events.index')
                ->with('error', 'Only draft events can be edited.');
        }

        $request->validate([
            'name'          => ['required', 'string', 'max:75'],
            'description'   => ['required', 'string'],
            'event_type'    => ['required', 'string'],
            'category_id'   => ['required', 'exists:categories,id'],
            'start_date'    => ['required', 'date'],
            'end_date'      => ['required', 'date', 'after:start_date'],
            'payment_model' => ['required', 'in:attendee_pays,manager_pays'],
            'cover_image'   => ['nullable', 'image', 'mimes:jpeg,png', 'max:2048'],
        ]);

        // Handle slug
        $slug = $request->slug
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        // Make slug unique excluding current event
        $originalSlug = $slug;
        $count = 1;
        while (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Handle cover image
        $coverImage = $event->cover_image;
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image')->store('events', 'public');
        }

        $event->update([
            'category_id'      => $request->category_id,
            'name'             => $request->name,
            'slug'             => $slug,
            'description'      => $request->description,
            'event_type'       => $request->event_type,
            'country'          => $request->country,
            'location'         => $request->location,
            'is_virtual'       => $request->is_virtual == '1',
            'virtual_link'     => $request->virtual_link,
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'timezone'         => $request->timezone,
            'is_recurring'     => $request->is_recurring == '1',
            'recurrence_rule'  => $request->recurrence_rule,
            'recurrence_end'   => $request->recurrence_end,
            'cover_image'      => $coverImage,
            'payment_model'    => $request->payment_model,
            'instagram'        => $request->instagram,
            'twitter'          => $request->twitter,
            'facebook'         => $request->facebook,
            'website'          => $request->website,
        ]);

        return redirect()->route('dashboard.events.index')
            ->with('success', 'Event updated successfully!');
    }
}
