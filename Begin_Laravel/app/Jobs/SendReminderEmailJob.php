<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\ReminderEmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendReminderEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        // Job này luôn chạy trên queue riêng "reminders", tách khỏi queue "default"
        $this->onQueue('reminders');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::query()
            ->whereNotNull('email')
            ->chunkById(100, function (Collection $users) {
                /** @var User $user */
                foreach ($users as $user) {
                    $user->notify(new ReminderEmailNotification);
                }
            });

        Log::info('SendReminderEmailJob: reminder emails dispatched.');
    }
}
