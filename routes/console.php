<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\CheckTaskReminders;

// ... يمكنك ترك الأوامر الموجودة في الملف كما هي

Schedule::job(new CheckTaskReminders)->everyMinute();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
