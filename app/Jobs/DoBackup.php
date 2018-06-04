<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;
use Carbon\Carbon;
use App\Backup;

class DoBackup implements ShouldQueue
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
        $tftpb = DB::table('tftp')->first();
        if (! $tftpb)
        {
            $tftp = "192.168.1.13";
        }
        else
        {
            $tftp = $tftpb->ip;
        }

        $a=$this->net;
        $ips=explode('a', $a);
        foreach ($ips as $ip) {
            
        $scans = DB::table('devices')->where('status','c')->where('nname',$ip)->get();
        $i=DB::table('backups')->max('id');

        if( $i == NULL)
        {
            $i=0;
        }

        if ($scans->isEmpty())
        {
            return;
        }

        foreach ($scans as $ip) {
            $i++;
            $nname = str_replace('/', '_', $ip->nname);
            $filepath = '/tftpboot/backups/'.$nname.'/'.$ip->dip;
            $current_time = Carbon::now()->toDateTimeString();
            $current_time = str_replace(' ', '_', $current_time);


            $out=shell_exec("snmpset -v 3 -u authbiat -l authPriv -a SHA -x AES -A biatbiat -X biatbiat $ip->dip 1.3.6.1.4.1.9.9.96.1.1.1.1.2.$i i 1");
            if (strpos($out, 'INTEGER') !== false)
            {
            exec("mkdir -p $filepath");
            // exec("cd $filepath");
            exec("touch $filepath/$current_time");
            exec("chmod 777 $filepath/$current_time");
            $filepath2="$filepath/$current_time";
            

                            exec("/mnt/hgfs/NetworkManager/snmp_files/snmpget_config $ip->dip $filepath $i $current_time $tftp $filepath2");
                do
                {
                    $x=false;
                    $output=shell_exec("/mnt/hgfs/NetworkManager/snmp_files/check_state $ip->dip $i");
                    if (strpos($output, 'INTEGER: 3') !== false)
                    {
                        $x=true;
                    }
                    else if (strpos($output, 'INTEGER: 2') !== false  || strpos($output, 'INTEGER: 1') !== false)
                    {
                        $x=false;
                    }
                    else
                    {
                        $x=true;   
                    }
                
                }while ($x == false);
                exec("/mnt/hgfs/NetworkManager/snmp_files/destroy_state $ip->dip $i");
            $backup = new Backup;
            $backup->ip = $ip->dip;
            $backup->nname= $ip->nname;
            $backup->created_at = $current_time;
            $backup->save(); 
            }



            }
        }
    }
}
