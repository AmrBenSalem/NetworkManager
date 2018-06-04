<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class DeviceController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    protected function index ()
    {
    	$devices=DB::table('devices')->get();
    	return View('/dashboard/devices')->with('devices',$devices);
    }

    protected function getconfig(Request $request)
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

                $ip = DB::table('devices')->where('dip',$request->input('read'))->first();
                if ($ip->status == "c")
                    {
                        $i=501;
                        $nname = str_replace('/', '_', $ip->nname);
                        $filepath = '/tftpboot/currentconf/'.$nname.'/'.$ip->dip;
                         // exec("mkdir -p $filepath");
                        // $current_time = Carbon::now()->toDateTimeString();
                        // $current_time = str_replace(' ', '_', $current_time);
                       
                        $out=shell_exec("snmpset -v 3 -u authbiat -l authPriv -a SHA -x AES -A biatbiat -X biatbiat $ip->dip 1.3.6.1.4.1.9.9.96.1.1.1.1.2.$i i 1");
                            if (strpos($out, 'INTEGER') !== false)
                            {
                            exec("mkdir -p $filepath");
                            // exec("cd $filepath");
                            exec("touch $filepath/current");
                            exec("chmod 777 $filepath/current");
                            $filepath2="$filepath/current";
                            exec("/mnt/hgfs/NetworkManager/snmp_files/snmpget_config $ip->dip $filepath $i current $tftp $filepath2");

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
                                        exec("/mnt/hgfs/NetworkManager/snmp_files/destroy_state $ip->dip $i");
                                        return back();
                                        // $x=true;   
                                    }
                        
                                }while ($x == false);

                            exec("/mnt/hgfs/NetworkManager/snmp_files/destroy_state $ip->dip $i");
                                    
                        }

                    }
                    else
                    {
                        return back();
                    }


            $nname = DB::table('devices')->where('dip',$request->input('read'))->value('nname');
            $nname = str_replace('/', '_', $nname);
            // $last = DB::table('backups')->where('ip',$request->input('read'))->orderBy('created_at','desc')->first()->created_at;
            // $last = str_replace(' ', '_', $last);
            $ip = $request->input('read');

            $output = shell_exec("cat /tftpboot/currentconf/$nname/$ip/current 2>&1");

            echo "<pre>$output</pre>";
            return ;
    }

    protected function getinterfaces (Request $request)
    {
    	$selected=preg_replace("/\([^)]+\)/","",$request->input('t'));
    	$interfaces = DB::table('intdevice')->where('ip',$selected)->get();
    	$output = '<h3> Interfaces : </h3>
        <div class="well col-xs-11 well-margin-top">
					<div class="table-responsive">
						<table class="table table-condensed" id="interface-table">
							<thead>
								<tr>
									<th>
										Interface name
									</th>
									<th>
										Ip Address / Vlan
									</th>
									<th>
										Type
									</th>
									<th>
										Mtu
									</th>
									<th>
										Speed
									</th>
									<th class="center-td">
										Admin status
									</th>
									<th class="center-td">
										Operational status
									</th>
								</tr>
							</thead>';
    	foreach ($interfaces as $interface) {

    		$output = $output.'<tr>';
    		$output = $output.'<td>'.$interface->description.'</td>';
    		$output = $output.'<td>'.$interface->intip.'</td>';
    		$output = $output.'<td>'.$interface->type.'</td>';
    		$output = $output.'<td>'.$interface->mtu.'</td>';
    		$output = $output.'<td>'.$interface->speed.'</td>';
    		if (strpos($interface->adminstatus, 'up') !== false)
    		{ 
    			$output = $output.'<td class="center-td"><div class="label label-success updown">'.strtoupper($interface->adminstatus).'</div></td>';
    		}
    		else
    		{
    			$output = $output.'<td class="center-td"><div class="label label-danger updown">'.strtoupper($interface->adminstatus).'</div></td>';
    		}
    		if (strpos($interface->operstatus, 'up') !== false)
    		{
    			$output = $output.'<td class="center-td"><div class="label label-success updown">'.strtoupper($interface->operstatus).'</div></td>';
    		}
    		else
    		{
    			$output = $output.'<td class="center-td"> <div class="label label-danger updown">'.strtoupper($interface->operstatus).'</div></td>';
    		}
    		
    		$output = $output.'</tr>';
    		
    	}
    	return $output;
    }


}
