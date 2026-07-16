<?php

namespace App\Http\Controllers\EventManager;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\OrderItem;
use App\Policies\EventPolicy;
use App\Services\Export\CsvExporter;

class AttendeeController extends Controller
{
    public function __construct(
        private CsvExporter $csvExporter,
    ) {}

    public function index(Event $event)
    {
        $this->authorize(EventPolicy::class.'.manageAttendees', $event);

        $attendees = OrderItem::completedForEvent($event->id)
            ->with(['order', 'ticket'])
            ->when(request('search'), function ($q) {
                $q->where(function ($query) {
                    $query->where('attendee_name', 'like', '%'.request('search').'%')
                        ->orWhere('attendee_email', 'like', '%'.request('search').'%');
                });
            })
            ->latest()
            ->paginate(20);

        $totalCheckedIn = OrderItem::completedForEvent($event->id)
            ->checkedIn()
            ->count();

        $totalAttendees = OrderItem::completedForEvent($event->id)
            ->count();

        return view('eventmanager.events.attendees', compact(
            'event',
            'attendees',
            'totalCheckedIn',
            'totalAttendees'
        ));
    }

    public function export(Event $event)
    {
        $this->authorize(EventPolicy::class.'.manageAttendees', $event);

        return $this->csvExporter->exportAttendees($event, 'attendees-'.$event->slug.'.csv');
    }
}
