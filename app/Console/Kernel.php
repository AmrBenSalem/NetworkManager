<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use DB;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DoScanCommand::class,
        Commands\DoPingCommand::class,
        Commands\DoBackupCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {


        // $schedule->command('inspire')
        //          ->hourly();
        // $schedule->call(function () {
        //     $this->dispatch(new DoScan());
        // })->everyMinute();

        // $networks = DB::table('networks')->get();
        $profiles = DB::table('profiles')->get();
        foreach ($profiles as $profile) {

            $networks=DB::table('networks')->where('profile',$profile->label)->where('status','enabled')->get();

        if ($networks)
        {
            $string="";
            foreach ($networks as $network) {
                $string=$string.$network->ip.'a';
            }
            $string = substr($string, 0, -1);
        if ($profile->pingtime =="minute")
        {
            $schedule->command('doping:ping '.$string)->cron("*/".$profile->pingnombre." * * * * *");
        }
        else if ($profile->pingtime == "hour")
        {
            if ($profile->pingnombre == 1)
            {
                $schedule->command('doping:ping '.$string)->cron("0 * * * * *");
            }
            else
            {
                $schedule->command('doping:ping '.$string)->cron("0 */".$profile->pingnombre." * * * *");
            }
        }
        else if ($profile->pingtime == "day")
        {
            if ($profile->pingnombre == 1)
            {
                $schedule->command('doping:ping '.$string)->cron("0 0 * * * *");
            }
            else
            {
                $schedule->command('doping:ping '.$string)->cron("0 0 */".$profile->pingnombre." * * *");
            }
            
        }

        if ($profile->scantime =="minute")
        {
            $schedule->command('doscan:scan '.$string)->cron("*/".$profile->scannombre." * * * * *");
        }
        else if ($profile->scantime == "hour")
        {
            if ($profile->scannombre == 1)
            {
                $schedule->command('doscan:scan '.$string)->cron("0 * * * * *");
            }
            else
            {
                $schedule->command('doscan:scan '.$string)->cron("0 */".$profile->scannombre." * * * *");
            }
            
        }
        else if ($profile->scantime == "day")
        {
            if ($profile->scannombre == 1)
            {
                $schedule->command('doscan:scan '.$string)->cron("0 0 * * * *");
            }
            else
            {
                $schedule->command('doscan:scan '.$string)->cron("0 0 */".$profile->scannombre." * * *");
            }
            
        }

        if ($profile->backuptime =="minute")
        {
            $schedule->command('dobackup:backup '.$string)->cron("*/".$profile->backupnombre." * * * * *");
        }
        else if ($profile->backuptime == "hour")
        {
            if ($profile->backupnombre == 1)
            {
                $schedule->command('dobackup:backup '.$string)->cron("0 * * * * *");
            }
            else
            {
                $schedule->command('dobackup:backup '.$string)->cron("0 */".$profile->backupnombre." * * * *");
            }

            
        }
        else if ($profile->backuptime == "day")
        {
            if ($profile->backupnombre == 1)
            {
                $schedule->command('dobackup:backup '.$string)->cron("0 0 * * * *");
            }
            else
            {
                $schedule->command('dobackup:backup '.$string)->cron("0 0 */".$profile->backupnombre." * * *");
            }
            
        }

    }
    
    // else
    // {
    //     $schedule->command('doping:ping')->everyMinute();
    //     $schedule->command('doscan:scan')->everyTenMinutes();
    //     $schedule->command('dobackup:backup')->cron("0 0 * * * *");


    // }

    }


        // $schedule->command('doscan:scan')->everyFiveMinutes();
        
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
