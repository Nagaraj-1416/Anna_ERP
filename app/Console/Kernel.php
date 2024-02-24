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
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('auto:update:allocation')->daily();
        $schedule->command('generate:po:request')->daily();
        //$schedule->command('generate:shop:po:request')->daily();
        $schedule->command('transaction:update:code')->everyFiveMinutes();
        $schedule->command('auto:terminate:work:hour')->everyMinute();
        //$schedule->command('backup:db')->twiceDaily(1, 23);

        /** cron test log */
//        $schedule->command('cron:test:log')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
