<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ExpireRentals;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ExpireRentals::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run every five minutes to mark past rentals expired.
        $schedule->command('rentals:expire')->everyFiveMinutes()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // Load routes/console.php if present and commands in Commands directory
        if (file_exists(app_path('Console\routes.php'))) {
            $this->load(app_path('Console\routes.php'));
        }

        $this->load(app_path('Console/Commands'));
    }
}
