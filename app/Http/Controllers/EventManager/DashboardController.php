<?php

namespace App\Http\Controllers\EventManager;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalEvents = Event::where('user_id', $user->id)->count();

        $totalTicketsSold = Order::whereHas('event', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('payment_status', 'completed')->sum('manager_earnings');

        $totalRevenue = Order::whereHas('event', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('payment_status', 'completed')->sum('manager_earnings');

        $upcomingEvents = Event::where('user_id', $user->id)
            ->where('status', 'published')
            ->where('start_date', '>', now())
            ->count();

        $recentEvents = Event::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalEvents',
            'totalTicketsSold',
            'totalRevenue',
            'upcomingEvents',
            'recentEvents'
        ));
    }
}
