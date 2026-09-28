<?php

use App\Jobs\SendReminderEmailJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Mỗi ngày lúc 08:00, dispatch job gửi email nhắc nhở vào queue "reminders"
Schedule::job(new SendReminderEmailJob)->dailyAt('08:00');
