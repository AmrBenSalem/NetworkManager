<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class LogsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    protected function index()
    {
    	$syslogs = DB::table('informs_unknown')->orderBy('traptime','desc')->get();
    	$informs = DB::table('informs')->orderBy('traptime','desc')->get();

    	foreach ($syslogs as $syslog) {
    		$tab = explode("():", $syslog->formatline);
    		if (isset($tab[4]))
            {
            $tab2 = explode("enterprises.", $tab[4]);
    		$syslog->formatline = $tab2[0];
    		$device = DB::table('devices')->where('dip',$syslog->hostname)->first();
    		$syslog->agentip = $device->sysname;
    		$syslog->enterprise = $device->nlabel;
            }
    	}

        if (! $syslogs->isEmpty())
        {
    	foreach ($informs as $inform) {
    		$device = DB::table('devices')->where('dip',$syslog->hostname)->first();
    		$inform->agentip = $device->sysname;
    		$inform->enterprise = $device->nlabel;
    	}
    }
    	return View('dashboard/logs')->with('syslogs',$syslogs)->with('informs',$informs);
    }
}
