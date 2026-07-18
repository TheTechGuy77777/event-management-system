<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'users:delete-unverified';

    /**
     * The console command description.
     */
    protected $description = 'Delete users who have not verified their email after 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = User::whereNull('email_verified_at')
            ->where('created_at', '<', now()->subDays(7))
            ->whereDoesntHave('events') // protects users who already created events
            ->delete();

        $this->info("Deleted {$deleted} unverified users.");

        return Command::SUCCESS;
    }
}
