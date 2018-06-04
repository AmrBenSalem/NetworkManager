<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;
use App\Device;
use App\Ping;
use Carbon\Carbon;
use App\Scan;

class DoPing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $net;
    public function __construct(String $networkss)
    {
        $this->net=$networkss;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $a=$this->net;
        $ips=explode('a', $a);
        $current_time = Carbon::now()->toDateTimeString();
        $status[0]=0;
        $status[1]=0;
        foreach ($ips as $ip) {

        $devices=DB::table('devices')->where('nname',$ip)->select('dip','nname')->get();
        
        if ($devices)
        {
        foreach ($devices as $device) {
            $output = shell_exec("nmap -n -sP $device->dip");
            if (strpos($output, '(0 hosts up)') !== false)
                {
                    $status[1]++;
                    DB::table('devices')->where('dip',$device->dip)->update(['status' => 'n']);
                    // $scan = new Scan;
                    // $scan->created_at = $current_time;
                    // $scan ->status = "failed";
                    // $scan ->ip = $device->dip;
                    // $scan ->nname = $device->nname;
                    // $scan -> save();

                    $ping = new Ping;
                    $ping->created_at = $current_time;
                    $ping ->status = "failed";
                    $ping ->ip = $device->dip;
                    $ping ->nname = $device->nname;
                    $ping -> save();

                }
            else
            {
                    $status[0]++;
                    DB::table('devices')->where('dip',$device->dip)->update(['status' =>'c']);
                    $ping = new Ping;
                    $ping->created_at = $current_time;
                    $ping ->status = "success";
                    $ping ->ip = $device->dip;
                    $ping ->nname = $device->nname;
                    $ping -> save();
            }
        }

            session(['pings' => $status]);
        }

        }
    }
}
