<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ScanController extends Controller
{
        
	protected function check()
	{
        $status[0]=0;
        $status[1]=0;
        $devices=DB::table('devices')->get();
        foreach ($devices as $device) {
        
        
            $last = DB::table('scans')->where('ip',$device->dip)->orderBy('created_at','desc')->first();
            if ($last)
            {
          	     if ($last->status == "success")
                 {
                    $status[0]++;
                 }
                else
                {
                    $status[1]++;
                }
        	}

        }
    	session(['scans' => $status]);
    	return $status;
    }
}
