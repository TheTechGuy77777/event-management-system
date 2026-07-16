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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAnnouncementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $timeout = 30;

    public function __construct(
        public int $userId,
        public string $userEmail,
        public string $userName,
        public string $subject,
        public string $message,
    ) {}

    public function handle(): void
    {
        $user = User::find($this->userId);

        if (! $user || ! $user->isEventManager() || ! $user->is_active) {
            return;
        }

        $notification = Notification::firstOrCreate(
            [
                'user_id' => $user->id,
                'title' => $this->subject,
                'message' => $this->message,
                'type' => 'info',
            ]
        );

        if ($notification->wasRecentlyCreated) {
            Mail::to($user->email)->send(
                new AnnouncementMail($this->subject, $this->message)
            );
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::error('SendAnnouncementJob failed', [
            'user_id' => $this->userId,
            'subject' => $this->subject,
            'error' => $e->getMessage(),
        ]);
    }
}
