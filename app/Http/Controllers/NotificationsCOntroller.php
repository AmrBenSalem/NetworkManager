<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Device;
use App\Ping;

class NotificationsCOntroller extends Controller
{
    protected function check ()
    {
        $informs = DB::table('informs')->orderBy('traptime','desc')->select('id','enterprise','hostname','traptime','formatline','seen')->take(10)->get();
        $string="";

        $c=0;
        foreach ($informs as $inform)
        { 
            $datetime=$inform->traptime;
            $carbon = Carbon::parse($datetime);
            $device = DB::table('devices')->where('dip',$inform->hostname)->first();
            $string = $string.'<div class="content">
                   <div class="notification-item new-notification">
                    <h4 class="item-title"><b> Device '.$device->sysname.'('.$device->dip.') in '.$device->nlabel.' Network : </b></h4>
                    <p class="text-info">'.$inform->formatline.'</p>
                    <p class="text-right timetext"><i>'.$carbon->diffForHumans(Carbon::now(),true).' ago</i></p>
                  </div>
                </div><hr class="no-margin-hr">';
            if ($inform->seen == "no" )
            {
                    $c++;
                    if ($inform->enterprise != "checked")
                    {
                        DB::table('informs')->where('id',$inform->id)->update(['enterprise' => "checked"]);
                        $this->pings();
                    }
            }
                
            
        }

        

        $tab[0]=$string;
        $tab[1]=$c;

        session(['notifications' => $string]);
        session(['notifnumber' => $c]);
        return $tab;
    }


    protected function seen ()
    {
    	DB::table('informs')->update(['seen' => 'y']);
    	session(['notifnumber' => 0]);
    }

    protected function pings ()
    {

        $current_time = Carbon::now()->toDateTimeString();
        $status[0]=0;
        $status[1]=0;

            $devices = DB::table('devices')->get();

        foreach ($devices as $device) {
            $output = shell_exec("nmap -n -sP $device->dip");
            if (strpos($output, '(0 hosts up)') !== false)
                {
                    $status[1]++;
                    DB::table('devices')->where('dip',$device->dip)->update(['status' => 'n']);

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
