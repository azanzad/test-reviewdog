<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //create user database
        $schedule->command('make:database')->everyMinute()->withoutOverlapping();

        //update user access token for amazone
        $schedule->command('updateaccesstoken:amazon')->everyThirtyMinutes()->withoutOverlapping();
        
        //fetch order from amazon
        $schedule->command('fetchorder:amazon')->hourly()->withoutOverlapping();
        
        //send request for seller feedback of amazon order
        $schedule->command('sellerfeedbacksolicitation:amazon')->hourly()->withoutOverlapping();
        
		//update sucucessfull request count
        $schedule->command('HourlySucucessfullCount:user')->hourly()->withoutOverlapping();
        
		
        //send daily request count mail
        $schedule->command('dailyrequest:user')->dailyAt('12:00')->withoutOverlapping();

        //send weekly request count mail
        $schedule->command('weeklyrequest:user')->weeklyOn(1, '12:00')->withoutOverlapping();

        //send monthly request count mail
        $schedule->command('monthlyrequest:user')->monthlyOn(1, '12:00')->withoutOverlapping();

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