<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function modify(Request $data)
    {
    	$email=DB::table('users')->where('id',$data['id'])->value('email');
    	
    	if ($data['email'] == $email)
    	{
    		 $this->validate($data, [
	            'name' => 'required|max:255',
    		]);

    	}
    	else 
    	{
    		  $this->validate($data, [
	            'name' => 'required|max:255',
	            'email' => 'required|email|max:255|unique:users',
    		]);

    	}

        DB::table('users')->where('id',$data['id'])->update(['name' => $data['name']]);
        $user = Auth::user();
        session(['user' => $user]);
        return back();
    }


    protected function get (Request $request)
    {
        $user = DB::table('users')->where('name',$request->input('t'))->first();
        $tab[0]=$user->name;
        $tab[1]=$user->email;
        $tab[2]=$user->role;

        return $tab;
    }
}
