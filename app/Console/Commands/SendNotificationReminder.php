<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Notifications\AttendanceReminderNotification;
use Illuminate\Console\Command;

class SendNotificationReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:send-notification-reminder';

    protected $signature = 'reminder:attendance';


    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Kirim reminder absensi pegawai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        $employees = Employee::with(['workSchedule', 'user'])
            ->where('is_active', true)
            ->get();

        foreach ($employees as $employee) {
            $schedule = $employee->workSchedule;
            if (!$schedule || !$employee->user) continue;

            $timezone = $schedule->timezone;
            $currentTime = now($timezone)->format('H:i');

            if (
                $currentTime === substr($schedule->time_in, 0, 5) ||
                $currentTime === substr($schedule->time_out, 0, 5)
            ) {
                $employee->user
                    ->notify(new AttendanceReminderNotification());
            }
        }
    }
}
