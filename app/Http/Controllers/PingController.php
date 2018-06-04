<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PingController extends Controller
{
    protected function check ()
    {
        $status[0]=0;
        $status[1]=0;
        $devices=DB::table('devices')->get();
        $online="<ul class='list-group'>";
        $offline="<ul class='list-group'>";
        foreach ($devices as $device) {
        
        
            $last = DB::table('pings')->where('ip',$device->dip)->orderBy('created_at','desc')->first();
            if ($last)
            {
                 if ($last->status == "success")
                 {
                    $online=$online."<li class='list-group-item'>".$device->sysname." (".$device->dip." ) in ".$device->nlabel."</li>";
                    $status[0]++;
                 }
                 else
                 {
                    $offline=$offline."<li class='list-group-item'>".$device->sysname." (".$device->dip." ) in ".$device->nlabel."</li>";
                    $status[1]++;
                 }
            }

        }
        if ($online == "<ul class='list-group'>")
        {
            $online = $online."No Device Reachable";
        }
        if ($offline == "<ul class='list-group'>")
        {
            $offline = $offline."No Device Unreachable";
        }
        $online=$online."</ul>";
        $offline=$offline."</ul>";

    	session(['pings' => $status]);
        session(['offline' => $offline]);
        session(['online' => $online]);
    	return $status;
    }
}
