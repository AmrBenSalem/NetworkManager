<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Network;
use DB;

class NetworkController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

	protected function add (Request $request)
	{
    	$this->validate($request, [
	           'ip' => 'required|max:255|unique:networks',
	           'nname' =>'required|max:255|unique:networks',
	           'profile' =>'required|min:2',
    	]);

		$network= new Network;
		$network-> ip = $request->input('ip');
		$network-> nname = $request->input('nname');
		$network-> profile = $request->input('profile');
		$network-> status = 'enabled';
		$network->save();
		$request->session()->push('networks',$network);
		return back();
	}

	protected function delete (Request $request)
	{
		 DB::table('networks')->where('ip','=',$request->input('deletenet'))->delete();
		 $networks = DB::table('networks')->get();
         session(['networks' => $networks]);
		 return back();
	}

	protected function modify (Request $request)
	{
		$network=DB::table('networks')->where('nname','=',$request->input('sn'))->first();

		if ($network->status == "enabled")
			 {
			 	DB::table('networks')->where('nname','=',$request->input('sn'))->update(['status' => 'disabled']);
			 	$networks = DB::table('networks')->get();
        		session(['networks' => $networks]);
			 	return "disabled";
			 }

			 else
			 {
			 	DB::table('networks')->where('nname','=',$request->input('sn'))->update(['status' => 'enabled']);
			 	$networks = DB::table('networks')->get();
        		session(['networks' => $networks]);
			 	return "enabled";
			 }
		
	}


	protected function changeprofile (Request $request)
    {
        if ( $request->input('sp') && $request->input('sn') )
        {
            DB::table('networks')->where('ip',$request->input('sn'))->update(['profile' => $request->input('sp')]);
           	$networks = DB::table('networks')->get();
         	session(['networks' => $networks]);
        }
    }
}
