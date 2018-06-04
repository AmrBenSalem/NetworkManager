<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use DB;
use App\Tftp;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function addprofile (Request $request)
    {

    		// $this->validate($request, [
      //               'label' => 'unique:profiles|required|max:191',
      //       ]);

    		if (DB::table('profiles')->where('label',$request->label)->first())
    		{
    			DB::table('profiles')->where('label',$request->input('label'))->update(['pingnombre' => $request->input('pingnombre') , 'pingtime' => $request->input('pingtime') , 'scannombre' => $request -> input ('scannombre') , 'scantime' => $request -> input('scantime') , 'backupnombre' => $request->input('backupnombre') , 'backuptime' => $request->input('backuptime')]);
    		}
    		else
    		{
    		$profile = new Profile ;
    		$profile -> label = $request->input('label');
    		$profile -> pingnombre = $request->input('pingnombre');
    		$profile -> pingtime = $request->input('pingtime');
    		$profile -> scannombre = $request -> input ('scannombre');
    		$profile -> scantime = $request -> input('scantime');
    		$profile -> backupnombre = $request->input('backupnombre');
    		$profile -> backuptime = $request->input('backuptime');
    		$profile -> save();
    		}
    		$profiles = DB::table('profiles')->get();
        	$active_profile = DB::table('profiles')->first();

        	session (['active_profile' => $active_profile]);
        	session (['profiles' => $profiles]);

    		return back();
    	

    }

    protected function manageprofile (Request $request)
    {
    	    $this->validate($request, [
                    'profiles' => 'required',
            ]);

            if ($request->input('delete'))
            {

            	if (DB::table('profiles')->count() > 1)
            	{
            		DB::table('profiles')->where('label',$request->input('profiles'))->delete();
            	}
            	else
            	{
            		//error no more rows
            		return back();
            	}
            }
            	
            

            $profiles = DB::table('profiles')->get();
        	$active_profile = DB::table('profiles')->first();
            if ($profiles)
            {
            	session (['active_profile' => $active_profile]);
            	session (['profiles' => $profiles]);
            }
            else
            {
                session()->forget('profiles');
                session()->forget('active_profile');
            }

    		return back();

    }

    protected function getprofile (Request $request)
    {
    	$profile = DB::table('profiles')->where('label',$request->input('sp'))->first();
    	$tab[0] = $profile->label;
    	$tab[1] = $profile->pingnombre;
    	$tab[2] = $profile->pingtime;
    	$tab[3] = $profile->scannombre;
    	$tab[4] = $profile->scantime;
    	$tab[5] = $profile->backupnombre;
    	$tab[6] = $profile->backuptime;
    	return $tab;
    }

    protected function tftp (Request $request)
    {
        $tftp = $request->input('tftp');
        $tftpb = DB::table('tftp')->first();
        if (! $tftpb)
        {
            $serv = new Tftp;
            $serv->ip = $tftp ;
            $serv->save();
        }
        else
        {
            DB::table('tftp')->update( ['ip' => $tftp ]);
        }

        $tftpb = DB::table('tftp')->first();
        session(['tftp' =>$tftpb->ip]);

        return back();
    }





}
