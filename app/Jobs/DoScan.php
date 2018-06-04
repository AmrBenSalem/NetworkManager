<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;
use App\Scan;
use App\Device;
use Carbon\Carbon;
use App\Intdevice;

class DoScan implements ShouldQueue
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

        exec('rm -f  /mnt/hgfs/NetworkManager/scans/scanip.txt');
        exec('rm -f  /mnt/hgfs/NetworkManager/scans/finalip.txt');
        exec('rm -f  /mnt/hgfs/NetworkManager/scans/failedip.txt');
        exec('rm -f  /mnt/hgfs/NetworkManager/scans/interfaces');
        exec('touch /mnt/hgfs/NetworkManager/scans/interfaces');
        $current_time = Carbon::now()->toDateTimeString();

        

        // if ($interfacess->first())
        // {
        // foreach ($interfacess as $inte) {
        //     if ($inte != "Unavailable")
        //     {
        //     exec("echo $inte >> /mnt/hgfs/NetworkManager/scans/interfaces");
        //     }
        // }
        // }

        if ($ips)
        {

        foreach ($ips as $ip) {

            if (DB::table('networks')->where('ip',$ip)->first()->status == "enabled")
            {
            exec("/mnt/hgfs/NetworkManager/scans/testscan $ip");
            }
            
        }

        if (file_exists("/mnt/hgfs/NetworkManager/scans/scanip.txt"))
        {
        exec("cat /mnt/hgfs/NetworkManager/scans/scanip.txt",$scanip);
        

        
        if (isset($scanip)) {
            
            foreach ($scanip as $line) {
                $line = preg_replace( "/\r|\n/", "", $line );
                $tab = explode(" ", $line);
                exec ("rm -f /mnt/hgfs/NetworkManager/scans/snmp_verification_ip");
                exec ("touch /mnt/hgfs/NetworkManager/scans/snmp_verification_ip");       
                exec ("/mnt/hgfs/NetworkManager/scans/get_informations $tab[0]");
                exec ("/mnt/hgfs/NetworkManager/scans/script $tab[0] $tab[1]");

                }

                    
            } 


        if (file_exists("/mnt/hgfs/NetworkManager/scans/finalip.txt"))
        {
        exec("cat /mnt/hgfs/NetworkManager/scans/finalip.txt",$lineip);
        }
        $ipe=DB::table('devices')->get();
        if (isset($lineip)) {

            foreach ($ips as $ip) {
            DB::table('devices')->where('nname',$ip)->update(['status' => 'n']);

            }
            foreach ($lineip as $line) {
                        $line = preg_replace( "/\r|\n/", "", $line );
                        // $line = str_replace('_', '/', $line);
                        
                        $tab = explode(" ", $line);
                        if ( ! DB::table('intdevice')->where('ip','<>',$tab[0])->where('intip',$tab[0])->first() )
                        {
                            $device = new Device;
                            $device->dip = $tab[0];
                            $device->nname =$tab[1];
                            $device->nlabel = DB::table('networks')->where('ip',$tab[1])->first()->nname;
                            exec ("/mnt/hgfs/NetworkManager/snmp_files/get_all_informations $tab[0]");
                            $output=shell_exec('cat /mnt/hgfs/NetworkManager/snmp_files/informations_tmp | grep "sysref" | cut -d ":" -f2');
                            $device->sysref=$output;
                            $output=shell_exec('cat /mnt/hgfs/NetworkManager/snmp_files/informations_tmp | grep "sysname" | cut -d ":" -f2');
                            $device->sysname=$output;
                            $output=shell_exec('cat /mnt/hgfs/NetworkManager/snmp_files/informations_tmp | grep "syssoftware" | cut -d ":" -f2');
                            $device->syssoftware=$output;
                            $output=shell_exec('cat /mnt/hgfs/NetworkManager/snmp_files/informations_tmp | grep "sysversion" | cut -d ":" -f2');
                            $device->sysversion=$output;
                            $device->status = "c";
                            if (Device::where('dip',$tab[0])->where('nname',$tab[1])->first())
                                {
                                    DB::table('devices')->where('dip',$tab[0])->where('nname',$tab[1])->update(['sysref'=> $device->sysref , 'sysname' => $device->sysname , 'syssoftware' => $device->syssoftware,'sysversion' => $device->sysversion,'status' => $device->status , 'nlabel' => $device->nlabel]);
                                }
                            else
                            {
                                $device->save();
                                // DB::table('scans')->where('ip',$tab[0])->where('nname',$tab[1])->where('created_at',$current_time)->update(['status' => 'success']);
                            }

                            $scan = new Scan;
                            $scan ->created_at = $current_time;
                            $scan ->status = "success";
                            $scan ->ip = $tab[0];
                            $scan ->nname = $tab[1];
                            $scan -> save();

                            // $intnumber = getIntNumber($tab[0]);
                            $this->getInterfaces($tab[0]);


                            }
                        }

                    }
                }

                            $offline_devices = DB::table('devices')->where('nname',$ips)->where('status','n')->get();
                            foreach ($offline_devices as $offdevice) {
                                    $scan2 = new Scan;
                                    $scan2 ->created_at = $current_time;
                                    $scan2 ->status = "failed";
                                    $scan2 ->ip = $offdevice->dip;
                                    $scan2 ->nname = $offdevice->nname;
                                    $scan2 -> save();
                    // fclose($handle);
                            }
        
        if (file_exists("/mnt/hgfs/NetworkManager/scans/failedip.txt"))
        {
        exec("cat /mnt/hgfs/NetworkManager/scans/failedip.txt",$failedip);
        }
        if (isset($failedip))
        {
            foreach ($failedip as $failed) {
                    $failed = preg_replace( "/\r|\n/", "", $failed );
                    $tab = explode(" ", $failed);
                    if ($ipe->contains($tab[0]))
                    {
                        $scan = new Scan;
                        $scan ->created_at = $current_time;
                        $scan ->status = "failed";
                        $scan ->ip = $tab[0];
                        $scan ->nname = $tab[1];
                        $scan -> save();
                    }

            }
        }
        // $filename = "/mnt/hgfs/NetworkManager/scans/scanip.txt";
        // $handle = fopen($filename, "r");
        // $contents = fread($handle, filesize($filename));
        // $scan = new Scan ;
        // $scan-> ip_list = $contents;
        // $scan->save();
    }
    // if ($counter == 5)
    // {
    //     exec('rm -f  /mnt/hgfs/NetworkManager/scans/temporary2');
    // }
    // exec('rm -f  /mnt/hgfs/NetworkManager/scans/temporary');
}

    protected function getInterfaces (String $ip)
    {
        // $intnumber = shell_exec("/mnt/hgfs/NetworkManager/snmp_files/get_int_number $ip");
        exec("/mnt/hgfs/NetworkManager/snmp_files/get_int_index $ip",$intfirst);
        exec("/mnt/hgfs/NetworkManager/snmp_files/get_int_index_ip $ip" , $indexip);
        exec("/mnt/hgfs/NetworkManager/snmp_files/get_int_ip $ip" ,$intips);
        $a=true;
        if (empty($indexip))
        {
            $f=false;
            $a=false;
        }
        else
        {
            $j=0;
            $f=true;
        }
        foreach ($intfirst as $i) { 
                $intdescr = shell_exec("/mnt/hgfs/NetworkManager/snmp_files/get_int_descr $ip $i");
                $inttype= shell_exec("/mnt/hgfs/NetworkManager/snmp_files/get_int_type $ip $i");
                $intmtu= shell_exec("/mnt/hgfs/NetworkManager/snmp_files/get_int_mtu $ip $i");
                $intspeed= shell_exec("/mnt/hgfs/NetworkManager/snmp_files/get_int_speed $ip $i");
                $intadmin= shell_exec("/mnt/hgfs/NetworkManager/snmp_files/get_int_admin $ip $i");
                $intoper= shell_exec("/mnt/hgfs/NetworkManager/snmp_files/get_int_oper $ip $i");
                if ($a == false)
                {
                    $intvlan = shell_exec("/mnt/hgfs/NetworkManager/snmp_files/get_int_vlan $ip $i");
                    if ($intvlan == NULL || strpos($intvlan, 'Null') !== false)
                    {
                        $intip = "Unavailable";
                    }
                    else
                    {
                        $intip = "Vlan ".$intvlan;
                    }
                }
                else if ($f && $i == $indexip[$j])
                {
                    $intip = $intips[$j];
                    if ( (count($indexip) - 1) == $j )
                    {
                        $f = false;
                    }
                    else
                    {
                        $j++;
                    }
                }
                else
                {
                    $intip = "Unavailable";
                }
            // if (strpos($intdescr, 'Null') == false)
            // {
                $interface = new Intdevice ;
                $interface -> ip = $ip;
                $interface -> id = $i;
                if ($intdescr == NULL || strpos($intdescr, 'Null') !== false)
                {
                    $intdescr = "Unavailable";
                }
                if ($inttype == NULL || strpos($inttype, 'Null') !== false)
                {
                    $inttype = "Unavailable";
                }
                if ($intmtu == NULL || strpos($intmtu, 'Null') !== false)
                {
                    $intmtu = "Unavailable";
                }
                if ($intspeed == NULL || strpos($intspeed, 'Null') !== false)
                {
                    $intspeed = "Unavailable";
                }
                if ($intadmin == NULL || strpos($intadmin, 'Null') !== false)
                {
                    $intadmin = "Unavailable";
                }
                if ($intoper == NULL || strpos($intoper, 'Null') !== false)
                {
                    $intoper = "Unavailable";
                }


                


                $interface -> description = $intdescr;
                $interface -> type = $inttype;
                $interface -> mtu = $intmtu;
                $interface -> speed = $intspeed/1000000; 
                $interface -> adminstatus = $intadmin;
                $interface -> operstatus = $intoper;
                $interface -> intip = $intip;
                // exec("echo $intip >> /mnt/hgfs/NetworkManager/scans/interfaces");

                if (Intdevice::where('ip',$ip)->where('id',$i)->first())
                {
                    DB::table('intdevice')->where('ip',$ip)->where('id',$i)->update(['description'=> $interface ->description , 'type' => $interface->type , 'mtu' => $interface->mtu,'speed' => $interface->speed,'adminstatus' => $interface->adminstatus , 'operstatus' => $interface->operstatus , 'intip' => $interface->intip]);
                }
                else
                {
                    $interface -> save();
                }
            // }
        }
    }

}
