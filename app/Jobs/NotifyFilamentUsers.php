<?php

namespace App\Jobs;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyFilamentUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly Notification $notification,
    )
    {
        //
    }

    public $tries = 3;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $recipients = User::query()->filamentUsers()->get();
        foreach ($recipients as $recipient) {
            $this->notification->sendToDatabase($recipient);
        }
    }
}
