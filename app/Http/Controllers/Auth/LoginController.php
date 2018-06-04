<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected function redirectTo()
    {
        $user = Auth::user();
        $networks = DB::table('networks')->get();
        $last = DB::table('pings')->orderBy('created_at','desc')->first();
        if ( $last )
        {
        $pings=DB::table('pings')->where('created_at',$last->created_at)->get();
        $statusp[0]=0;
        $statusp[1]=0;

        foreach ($pings as $ping) {
            if ($ping ->status =="success")
            {
                $statusp[0]++;
            }
            else
            {
                $statusp[1]++;
            }
        }
        session(['pings' => $statusp]);

        }
        else
        {
            $statusp[0]=0;
            $statusp[1]=0;
            session(['pings' => $statusp]);
        }


        

        // $last2 = DB::table('scans')->orderBy('created_at','desc')->first();
        // if ( $last2 )
        // {
        // $scans= DB::table('scans')->where('created_at',$last2->created_at)->get();
        // $statuss[0]=0;
        // $statuss[1]=0;

        // foreach ($scans as $scan) {
        //     if ($scan ->status =="success")
        //     {
        //         $statuss[0]++;
        //     }
        //     else
        //     {
        //         $statuss[1]++;
        //     }
        // }
        // session(['scans' => $statuss]);
        // }



        $profiles = DB::table('profiles')->get();
        if(! $profiles->isEmpty())
        {
            $active_profile = DB::table('profiles')->first();
            session (['active_profile' => $active_profile]);
            session (['profiles' => $profiles]);
        }
        

        $users = DB::table('users')->get();
        if (! $users->isEmpty())
        {
            session(['users' => $users]);
        }

        $tftp = DB::table('tftp')->first();
        if (! $tftp)
        {
            $tftp = "192.168.1.13";
        }
        session(['tftp' => $tftp->ip]);

        session(['networks' => $networks]);
        session(['user' => $user]);

        
        return '/dashboard';
    }
    // protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
}
