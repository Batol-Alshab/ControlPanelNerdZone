<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\reminderToTask;
use Carbon\Carbon;

class CheckTaskReminders  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // ابحث عن المهام التي لم تكتمل وذكرها اقترب
        $tasks = Task::where('created_at', '>=', now()->subHours(24))
            ->where('percent', '!=', 100) // تأكد أن المهمة لم تكتمل
            ->where('reminder_time', '<=', Carbon::now()->addMinutes(2)) // اقترب وقت التذكير (خلال الدقيقتين القادمتين)
            ->where('reminder_time', '>', Carbon::now()) // لم يمر الوقت بعد
            ->get();

        foreach ($tasks as $task) {
            // أرسل الـ Event
            event(new reminderToTask($task));
        }
    }
}
