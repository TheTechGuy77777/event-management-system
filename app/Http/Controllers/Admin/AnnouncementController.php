<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendAnnouncementJob;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $totalManagers = User::where('role', 'event_manager')->count();

        $managers = User::where('role', 'event_manager')
            ->where('is_active', true)
            ->where('is_banned', false)
            ->orderBy('name')
            ->get();

        $announcements = Announcement::with('sender')
            ->latest()
            ->paginate(10);

        return view('admin.announcements', compact(
            'totalManagers',
            'managers',
            'announcements'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
            'recipient_id' => ['nullable', 'exists:users,id'],
        ]);

        $subject = $request->input('subject');
        $message = $request->input('message');

        // Determine recipients
        if ($request->recipient_id) {
            // Specific manager
            $managers = User::where('id', $request->recipient_id)
                ->where('role', 'event_manager')
                ->get();
            $recipientType = 'specific';
            $recipientName = $managers->first()?->name;
        } else {
            // All active managers
            $managers = User::where('role', 'event_manager')
                ->where('is_active', true)
                ->where('is_banned', false)
                ->get();
            $recipientType = 'all';
            $recipientName = null;
        }

        // Dispatch jobs to queue
        foreach ($managers as $manager) {
            SendAnnouncementJob::dispatch($manager, $subject, $message);
        }

        // Save announcement history
        Announcement::create([
            'sent_by' => Auth::id(),
            'subject' => $subject,
            'message' => $message,
            'recipient_type' => $recipientType,
            'recipient_name' => $recipientName,
            'recipients_count' => $managers->count(),
        ]);

        return back()->with('success', 'Announcement sent to '.$managers->count().' manager(s) successfully!');
    }
}
