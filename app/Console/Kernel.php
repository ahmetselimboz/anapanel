<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('inspire')->hourly();
        $schedule->command('post:archive')->dailyAt('00:00');// schedule run post archive command
        $schedule->command('cache:clear-images')->dailyAt('02:00');
        $schedule->command('sitemap:generate news')->everyFiveMinutes();
        $schedule->command('sitemap:generate article')->everyFourHours();
        $schedule->command('sitemap:generate category')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

}
