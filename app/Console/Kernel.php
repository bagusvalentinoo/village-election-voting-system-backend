<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('telescope:prune')->daily()
            ->then(function () {
                info('Telescope pruned successfully');
            });
        $schedule->command('passport:purge')->dailyAt('00:01')
            ->then(function () {
                info('Passport tokens purged successfully');
            });
    }
}
