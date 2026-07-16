<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    public function index()
    {
        $managers = User::where('role', 'event_manager')
            ->with('events')
            ->latest()
            ->paginate(15);

        return view('admin.managers', compact('managers'));
    }

    public function suspend(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'You cannot modify another admin account.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot modify your own account.');
        }

        $user->update(['is_active' => false]);

        return back()->with('success', $user->name.' has been suspended.');
    }

    public function ban(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'You cannot modify another admin account.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot modify your own account.');
        }

        $user->update(['is_active' => false, 'is_banned' => true]);
        $user->events()->where('status', 'published')->update(['status' => 'cancelled']);

        return back()->with('success', $user->name.' has been permanently banned.');
    }

    public function reactivate(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'You cannot modify another admin account.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot modify your own account.');
        }

        $user->update(['is_active' => true, 'is_banned' => false]);

        return back()->with('success', $user->name.' has been reactivated.');
    }
}
