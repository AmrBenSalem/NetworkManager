<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Scan;
use App\Device;
use Carbon\Carbon;
use App\Intdevice;
use Illuminate\Support\Facades\Auth;

class ConfigureController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    protected function index ()
    {   
        $user= Auth::user()->id;
    	$devices = DB::table('devices')->where('status','c')->get();
        $templates = DB::table('templates')->where('user_id',$user)->get();
        $templates2 = DB::table('templates')->where('user_id','<>',$user)->get();
        $networks = DB::table('networks')->where('status','enabled');
    	return View("/dashboard/configure")->with('devices',$devices)->with('templates',$templates)->with('templates2',$templates2)->with('networks',$networks);
    }

    public function scan(Request $request)
    {

         $ips=$request->input('sn');
        
        

        exec('rm -f  /mnt/hgfs/NetworkManager/scans/instant/scanip.txt');
        exec('rm -f  /mnt/hgfs/NetworkManager/scans/instant/finalip.txt');
        exec('rm -f  /mnt/hgfs/NetworkManager/scans/instant/failedip.txt');
        exec('rm -f  /mnt/hgfs/NetworkManager/scans/instant/interfaces');
        exec('touch /mnt/hgfs/NetworkManager/scans/instant/interfaces');
        $current_time = Carbon::now()->toDateTimeString();


        if ($ips)
        {

        foreach ($ips as $ip) {

            exec("/mnt/hgfs/NetworkManager/scans/instant/testscan $ip");
            
            
        }

        if (file_exists("/mnt/hgfs/NetworkManager/scans/instant/scanip.txt"))
        {
        exec("cat /mnt/hgfs/NetworkManager/scans/instant/scanip.txt",$scanip);
        

        
        if (isset($scanip)) {
            
            foreach ($scanip as $line) {
                $line = preg_replace( "/\r|\n/", "", $line );
                $tab = explode(" ", $line);
                exec ("rm -f /mnt/hgfs/NetworkManager/scans/instant/snmp_verification_ip");
                exec ("touch /mnt/hgfs/NetworkManager/scans/instant/snmp_verification_ip");       
                exec ("/mnt/hgfs/NetworkManager/scans/instant/get_informations $tab[0]");
                exec ("/mnt/hgfs/NetworkManager/scans/instant/script $tab[0] $tab[1]");

                }

                    
            } 


        if (file_exists("/mnt/hgfs/NetworkManager/scans/instant/finalip.txt"))
        {
        exec("cat /mnt/hgfs/NetworkManager/scans/instant/finalip.txt",$lineip);
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
                            exec ("/mnt/hgfs/NetworkManager/snmp_files/instant/get_all_informations $tab[0]");
                            $output=shell_exec('cat /mnt/hgfs/NetworkManager/snmp_files/instant/informations_tmp | grep "sysref" | cut -d ":" -f2');
                            $device->sysref=$output;
                            $output=shell_exec('cat /mnt/hgfs/NetworkManager/snmp_files/instant/informations_tmp | grep "sysname" | cut -d ":" -f2');
                            $device->sysname=$output;
                            $output=shell_exec('cat /mnt/hgfs/NetworkManager/snmp_files/instant/informations_tmp | grep "syssoftware" | cut -d ":" -f2');
                            $device->syssoftware=$output;
                            $output=shell_exec('cat /mnt/hgfs/NetworkManager/snmp_files/instant/informations_tmp | grep "sysversion" | cut -d ":" -f2');
                            $device->sysversion=$output;
                            $device->status = "c";
                            if (Device::where('dip',$tab[0])->where('nname',$tab[1])->first())
                                {
                                    DB::table('devices')->where('dip',$tab[0])->where('nname',$tab[1])->update(['sysref'=> $device->sysref , 'sysname' => $device->sysname , 'syssoftware' => $device->syssoftware,'sysversion' => $device->sysversion,'status' => $device->status , 'nlabel' => $device->nlabel]);
                                }
                            else
                            {
                                $device->save();
                            }

                            $scan = new Scan;
                            $scan ->created_at = $current_time;
                            $scan ->status = "success";
                            $scan ->ip = $tab[0];
                            $scan ->nname = $tab[1];
                            $scan -> save();

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
                            }
        
        if (file_exists("/mnt/hgfs/NetworkManager/scans/instant/failedip.txt"))
        {
        exec("cat /mnt/hgfs/NetworkManager/scans/instant/failedip.txt",$failedip);
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
    }
    return;
}

    protected function getInterfaces (String $ip)
    {
        // $intnumber = shell_exec("/mnt/hgfs/NetworkManager/snmp_files/instant/get_int_number $ip");
        exec("/mnt/hgfs/NetworkManager/snmp_files/instant/get_int_index $ip",$intfirst);
        exec("/mnt/hgfs/NetworkManager/snmp_files/instant/get_int_index_ip $ip" , $indexip);
        exec("/mnt/hgfs/NetworkManager/snmp_files/instant/get_int_ip $ip" ,$intips);
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
                $intdescr = shell_exec("/mnt/hgfs/NetworkManager/snmp_files/instant/get_int_descr $ip $i");
                $inttype= shell_exec("/mnt/hgfs/NetworkManager/snmp_files/instant/get_int_type $ip $i");
                $intmtu= shell_exec("/mnt/hgfs/NetworkManager/snmp_files/instant/get_int_mtu $ip $i");
                $intspeed= shell_exec("/mnt/hgfs/NetworkManager/snmp_files/instant/get_int_speed $ip $i");
                $intadmin= shell_exec("/mnt/hgfs/NetworkManager/snmp_files/instant/get_int_admin $ip $i");
                $intoper= shell_exec("/mnt/hgfs/NetworkManager/snmp_files/instant/get_int_oper $ip $i");
                if ($a == false)
                {
                    $intvlan = shell_exec("/mnt/hgfs/NetworkManager/snmp_files/instant/get_int_vlan $ip $i");
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
                $interface -> speed = $intspeed; 
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

    protected function create (Request $request)
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

        if ( ! $request->input('ch'))
        {
            return redirect('/dashboard/configure')->with('failconf',"Please select at least one device");
        }
        if ( ! $request->input('select_template'))
        {
            return redirect('/dashboard/configure')->with('failconf',"Please select a template");
        }


        $file_content_uppercase=file_get_contents('/mnt/hgfs/NetworkManager/snmp_files/list_uppercase');
        $filepath  = preg_replace('/\s+/', '_', $request->input('select_template'));
        // $filepath = $request -> input('select_template');
        // $temporary=$filepath.'_Prototype';
        $oldfilepath='/mnt/hgfs/NetworkManager/snmp_files/'.$filepath;
        $filepath = '/tftpboot/configPrototype/'.$filepath;
        $filepathprototype = $filepath.'_Prototype';
        // exec("cat $oldfilepath > $filepathprototype");
        $file_content=file_get_contents($filepathprototype);
        $inputs = explode("\n",$file_content_uppercase);
        $inputs = array_filter($inputs);
        $i=0;
        $j=0;
        $k=0;
        $ips=$request->input('ch');

       foreach ($inputs as $input) {
            

            if ( $input == "IP" )
            {
                $temp=$request->input('ip');
                $new=$temp[$i].'.'.$temp[$i+1].'.'.$temp[$i+2].'.'.$temp[$i+3];
                $i=$i+4;

            }

            else if ($input == "MASK")
            {                
                $temp=$request->input('mask');
                $new=$temp[$j].'.'.$temp[$j+1].'.'.$temp[$j+2].'.'.$temp[$j+3];
                $j=$j+4;

            }

            else if ($input == "PASSWORD")
            {                
                $temp=$request->input('password');
                $new=$temp[$k];
                $k++;

            }

            else if ($input != "")
            {                
                $temp=$request->input($input);
                $new=$temp[0];

            }

            $from = '/'.preg_quote($input, '/').'/';
            $file_content = preg_replace($from, $new, $file_content, 1);
            // return $new;
            // $file_content = str_replace_first($input,$new,$file_content);

        }

        
        $file=fopen($filepathprototype, 'w');
        fwrite($file, $file_content);
        $id=DB::table('templates')->where('title',$request->input('select_template'))->value('id');
        $id++;
        $i=0;
        foreach ( $ips as $ip) {
            $out=shell_exec("snmpset -v 3 -u authbiat -l authPriv -a SHA -x AES -A biatbiat -X biatbiat $ip 1.3.6.1.4.1.9.9.96.1.1.1.1.2.$id i 1");
            if ($out == "")
            {
                        $tab[$i]=$ip;
                        $i++;
            }
            else
            {
                exec("/mnt/hgfs/NetworkManager/snmp_files/snmpset_config $ip $filepathprototype $id $tftp");
                do
                {
                    $x=false;
                    $output=shell_exec("/mnt/hgfs/NetworkManager/snmp_files/check_state $ip $id");
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
                        $tab[$i]=$ip;
                        $i++;
                        $x=true;
                    }
                    
                }while ($x == false);
                exec("/mnt/hgfs/NetworkManager/snmp_files/destroy_state $ip $id");

            }

        }

        
        if (isset($tab))
        {
            $x="Failed. Configuration failed on ip(s) : <br />";
            foreach ($tab as $ep) {
               $x = $x."-".$ep."<br />";
            }

            return redirect('/dashboard/configure')->with('failconf',$x);
        }
        return redirect('/dashboard/configure')->with('successconfigure','success');
        // $file_content = preg_replace($from, "new", $file_content, 1);
        


    }



}
