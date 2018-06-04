<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Backup;
use App\Restore;

class BackupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function index()
    {
    	// $max=DB::table('backups')->max('bnumber');
    	// $backups = DB::table('backups')->where('bnumber',$max);
        // >with('backups',$backups) 
        $networks = DB::table('backups')->distinct()->pluck("nname");
        $backups = DB::table('backups')->distinct()->select('ip','nname')->get();

        if ($networks->isEmpty() || $backups->isEmpty())
        {
            return View("/dashboard/backup");
        }
        else
        {
        
        // $backups = $query->orderBy('created_at','desc')->get();

        $i=0; 
        foreach ($backups as $backup) {

            $backup->created_at = DB::table('backups')->where('ip',$backup->ip)->orderBy('created_at','desc')->first()->created_at;
            // $tab[$i]= DB::table('backups')->where('ip',$backup->ip)->orderBy('created_at','desc')->first()->created_at;
            // $i++;
        }


    	return View("/dashboard/backup")->with('backups',$backups)->with('networks',$networks);
        }
    }

    protected function back()
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

		$scans = DB::table('devices')->where('status','c')->get();
        $i=DB::table('backups')->max('id');

        if( $i == NULL)
        {
        	$i=0;
        }

        if ($scans->isEmpty())
        {
            return '<div class="alert alert-danger alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <strong>Failed !</strong> No device is connected.
            </div>';

        }
        $j=0;
        foreach ($scans as $ip) {
        	$i++;
            $nname = str_replace('/', '_', $ip->nname);
            $filepath = '/tftpboot/backups/'.$nname.'/'.$ip->dip;
            // exec("mkdir -p $filepath");
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
                        $tab[$j]=$ip->dip;
                        $j++;
                        $x=true;   
                    }
                
                }while ($x == false);
            
            $backup = new Backup;
            // $backup->bnumber = $getbnumber+1;
            $backup->ip = $ip->dip;
            $backup->nname= $ip->nname;
            $backup->created_at = $current_time;
            $backup->save(); 

            }
            else
            {
                        $tab[$j]=$ip->dip;
                        $j++;
            }
            exec("/mnt/hgfs/NetworkManager/snmp_files/destroy_state $ip->dip $i");


        }   

        if (isset($tab))
        {
            $x="<b>Failed.</b> Backup failed on ip(s) : <br />";
            foreach ($tab as $ep) {
               $x = $x."-".$ep."<br />";
            }
            $x ='<div class="alert alert-danger alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$x.'</div>';
            return $x;
        }
        return '<div class="alert alert-success alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <strong>Success !</strong> Backup done successfully.
            </div>';


    }

    protected function manage (Request $request)
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
        if ($request->input('read'))
        {
            $nname = DB::table('devices')->where('dip',$request->input('read'))->value('nname');
            $nname = str_replace('/', '_', $nname);
            $last = DB::table('backups')->where('ip',$request->input('read'))->orderBy('created_at','desc')->first()->created_at;
            $last = str_replace(' ', '_', $last);
            $ip = $request->input('read');

            $output = shell_exec("cat /tftpboot/backups/$nname/$ip/$last 2>&1");

            echo "<pre>$output</pre>";
            return ;
 
        }
    	// $devices = DB::table('devices')->where('status','c')->get();
        else
        {
            if ($request->input('backup'))
            {
                $ip = DB::table('devices')->where('dip',$request->input('backup'))->first();
                if ($ip->status == "c")
                    {
                        $i=DB::table('backups')->max('id');
                        $nname = str_replace('/', '_', $ip->nname);
                        $filepath = '/tftpboot/backups/'.$nname.'/'.$ip->dip;
                         // exec("mkdir -p $filepath");
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
                                    else if (strpos($output, 'INTEGER: 2') !== false  || strpos($output, 'INTEGER: 1') !== false) {
                                        $x=false;
                                    }

                                    else
                                    {
                                        $tab[0]=$ip->dip;
                                        // $i++;
                                        $x=true;   
                                    }
                        
                                }while ($x == false);

                            exec("/mnt/hgfs/NetworkManager/snmp_files/destroy_state $ip->dip $i");
                                    
                        }
                        else
                        {
                            $tab[0]=$ip->dip;
                        }
                        if (isset($tab))
                        {
                            $msg="<strong>Failed !</strong> Backup failed on the ip : ".$tab[0];
                            return redirect("/dashboard/backup")->with('notconnected',$msg);
                        }
                        $backup = new Backup;
                        // $backup->bnumber = $getbnumber+1;
                        $backup->ip = $ip->dip;
                        $backup->nname= $ip->nname;
                        $backup->created_at = $current_time;
                        $backup->save();
                        return redirect("/dashboard/backup")->with('backupsuccess','success');
                    }
                    else
                    {
                        return redirect("/dashboard/backup")->with('notconnected',"<strong>Failed ! </strong> This device is not connected.");
                    }
            }
            else
            {
                $ip = DB::table('devices')->where('dip',$request->input('restore'))->first();
                if ($ip->status == "c")
                    {
                        $i=DB::table('backups')->max('id');
                        $lastone=DB::table('backups')->where('ip',$ip->dip)->orderBy('created_at','desc')->first()->created_at;
                        $nname = str_replace('/', '_', $ip->nname);
                        $lastone = str_replace(' ', '_', $lastone);
                        $filepath = '/tftpboot/backups/'.$nname.'/'.$ip->dip.'/'.$lastone;

                        $out=shell_exec("snmpset -v 3 -u authbiat -l authPriv -a SHA -x AES -A biatbiat -X biatbiat $ip->dip 1.3.6.1.4.1.9.9.96.1.1.1.1.2.$i i 1");
                        if (strpos($out, 'INTEGER') !== false)
                        {
                        exec("/mnt/hgfs/NetworkManager/snmp_files/snmpset_startconfig $ip->dip $filepath $i $tftp");


                        do
                        {
                            $x=false;
                            $output=shell_exec("/mnt/hgfs/NetworkManager/snmp_files/check_state $ip->dip $i");
                            //hneee
                            if (strpos($output, 'INTEGER: 3') !== false)
                            {
                                $x=true;
                            }
                            else if (strpos($output, 'INTEGER: 2') !== false  || strpos($output, 'INTEGER: 1') !== false) {
                                $x=false;
                            }
                            else
                            {
                                $tabr[0]=$ip->dip;
                                // $i++;
                                $x=true;   
                            }
                
                        }while ($x == false);
                        }
                        exec("/mnt/hgfs/NetworkManager/snmp_files/destroy_state $ip->dip $i");

                        if (isset($tabr))
                        {
                            $msg="<strong>Failed !</strong> Restore failed on the ip : ".$tabr[0];
                            return redirect("/dashboard/backup")->with('notconnected',$msg);
                        }

                        $customfile="/tftpboot/configPrototype/reboot";

                         $oute=shell_exec("snmpset -v 3 -u authbiat -l authPriv -a SHA -x AES -A biatbiat -X biatbiat $ip->dip 1.3.6.1.4.1.9.9.96.1.1.1.1.2.$i i 1");
                        if (strpos($oute, 'INTEGER') !== false)
                        {
                        exec("/mnt/hgfs/NetworkManager/snmp_files/snmpset_config $ip->dip $customfile $i $tftp");
                        do
                        {
                            $x=false;
                            $output=shell_exec("/mnt/hgfs/NetworkManager/snmp_files/check_state $ip->dip $i");
                            if (strpos($output, 'INTEGER: 3') !== false)
                            {
                                $x=true;
                            }
                            else if (strpos($output, 'INTEGER: 2') !== false  || strpos($output, 'INTEGER: 1') !== false) {
                                $x=false;
                            }
                            else
                            {
                                $tabr[0]=$ip->dip;
                                // $i++;
                                $x=true;   
                            }
                
                        }while ($x == false);
                        }
                        exec("/mnt/hgfs/NetworkManager/snmp_files/destroy_state $ip->dip $i");


                        if (isset($tabr))
                        {
                            $msg="<strong>Failed !</strong> Restore failed on the ip : ".$tabr[0] ." <br /> But the configuration has been updated to startup-config. Try rerunning the restore or rebooting the device.";
                            return redirect("/dashboard/backup")->with('notconnected',$msg);
                        }

                        $current_time = Carbon::now()->toDateTimeString();
                        $restore = new Restore;
                        $restore->ip = $ip->dip;
                        $restore->nname= $ip->nname;
                        $restore->created_at = $current_time;
                        $restore->save();
                        return redirect("/dashboard/backup")->with('successrestore','success');
                    }
                else
                {
                        return redirect("/dashboard/backup")->with('notconnected',"<strong>Failed ! </strong> This device is not connected.");                    
                }


            }
        }
    	// foreach($devices as $device)
    	// {
    	// 	$backup = DB::table('backups')->where('ip',$device->dip)->where('nname',$device->nname)->orderBy('created_at','desc')->first();
    	// 	$nname = str_replace('/', '_', $device->nname);
    	// 	$filepath = '/tftpboot/backups/'.$backup->systime.'/'.$nname.'/'.$device->dip;
    	// 	exec("/mnt/hgfs/NetworkManager/snmp_files/snmpset_config $device->dip $filepath $backup->id" ,$output);
    	// }
    	// 	return $output;
    	return redirect("/dashboard/backup")->with('successrestore','success');
    }
}
