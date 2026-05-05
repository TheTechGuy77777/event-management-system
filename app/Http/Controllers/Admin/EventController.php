<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $query = Event::with(['user', 'category', 'tickets']);

        if (request('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }

        if (request('manager')) {
            $query->where('user_id', request('manager'));
        }

        $events = $query->latest()->paginate(15);

        return view('admin.events', compact('events'));
    }
}
