<?php

namespace App\Jobs;

use App\Mail\AnnouncementMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAnnouncementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User $manager,
        public string $subject,
        public string $message,
    ) {}

    public function handle(): void
    {
        // Create in-app notification
        Notification::create([
            'user_id' => $this->manager->id,
            'title'   => $this->subject,
            'message' => $this->message,
            'type'    => 'info',
        ]);

        // Send email
        Mail::to($this->manager->email)->send(
            new AnnouncementMail($this->subject, $this->message)
        );
    }
}
