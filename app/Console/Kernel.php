<?php

namespace App\Console;

use App\Console\Commands\UpdateCurrentTasks;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        UpdateCurrentTasks::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
         $schedule
             ->command('update:current:tasks')
             ->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
