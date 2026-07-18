<?php

namespace App\Http\Controllers\EventManager;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventManager\StoreEventRequest;
use App\Http\Requests\EventManager\UpdateEventRequest;
use App\Mail\EventCancelledMail;
use App\Models\Category;
use App\Models\Event;
use App\Models\Order;
use App\Policies\EventPolicy;
use App\Services\Event\EventPublishService;
use App\Services\Event\EventService;
use App\Services\Export\CsvExporter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller
{
    public function __construct(
        private EventService $eventService,
        private EventPublishService $eventPublishService,
        private CsvExporter $csvExporter,
    ) {}

    public function index()
    {
        $events = Event::forManager(Auth::id())
            ->with(['tickets', 'orders', 'category'])
            ->latest()
            ->paginate(10);

        return view('eventmanager.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Cache::remember('active_categories', 3600, function () {
            return Category::where('is_active', true)->get();
        });

        return view('eventmanager.events.create', compact('categories'));
    }

    public function store(StoreEventRequest $request)
    {
        $event = $this->eventService->createEvent(
            $request->validated(),
            $request->file('cover_image'),
            $request->tickets ?? [],
            $request->lineup ?? []
        );

        if ($request->action === 'publish') {
            if ($event->tickets->isEmpty()) {
                return redirect()->route('dashboard.events.index')
                    ->with('error', 'Event saved as draft. Add at least one ticket before publishing.');
            }

            $this->eventPublishService->publish($event);

            return redirect()->route('dashboard.events.index')
                ->with('success', 'Your event is now live! 🎉');
        }

        return redirect()->route('dashboard.events.index')
            ->with('success', 'Event created successfully as a draft!');
    }

    public function publish(Event $event)
    {
        $this->authorize(EventPolicy::class . '.publish', $event);

        $this->eventPublishService->publish($event);

        return redirect()->route('dashboard.events.index')
            ->with('success', 'Your event is now live! 🎉');
    }

    public function destroy(Event $event)
    {
        $this->authorize(EventPolicy::class . '.delete', $event);

        $orders = Order::where('event_id', $event->id)
            ->where('payment_status', 'completed')
            ->with('items')
            ->get();

        $event->update(['status' => 'cancelled']);

        foreach ($orders as $order) {
            Mail::to($order->buyer_email)
                ->send(new EventCancelledMail($event, $order));

            sleep(1);
        }

        $event->delete();

        return redirect()->route('dashboard.events.index')
            ->with('success', 'Event cancelled. All attendees have been notified.');
    }

    public function edit(Event $event)
    {
        $this->authorize(EventPolicy::class . '.update', $event);

        $categories = Cache::remember('active_categories', 3600, function () {
            return Category::where('is_active', true)->get();
        });

        return view('eventmanager.events.edit', compact('event', 'categories'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->authorize(EventPolicy::class . '.update', $event);

        $this->eventService->updateEvent(
            $event,
            $request->validated(),
            $request->file('cover_image')
        );

        return redirect()->route('dashboard.events.index')
            ->with('success', 'Event updated successfully!');
    }
}
