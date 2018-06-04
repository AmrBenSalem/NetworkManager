<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Template;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


	protected function index ()
	{
		$templates = DB::table('templates')->get();
		return view('dashboard/addtemplate',['templates' => $templates]);
	}




    protected function create (Request $request)
    {
        if ($request->input('save'))
        {
            $this->validate($request, [
                    'commands' => 'required|max:191'
            ]);

        $title_txt    = (string) $request->input('save');
        $title_txt    = preg_replace('/\s+/', '_', $title_txt);
        $commands_txt = (string) $request -> input('commands');
        $filepath = "/mnt/hgfs/NetworkManager/snmp_files/".$title_txt;
        $file = fopen($filepath,'w');
        fwrite($file,$commands_txt);
        fclose($file);
        return redirect('/dashboard/template')->with('successedit','success');

        }
        else
        {
    	    $this->validate($request, [
    	            'title' => 'required|max:191|unique:templates',
    	            'commands' => 'required|max:191',
    	    ]);

	     // $validator = Validator::make($request->all(), [
      //       'title'    => 'required|max:191|unique:templates',
      //       'commands' => 'required|max:191',
      //   ]);

	     // if ($validator->fails()) {
      //       return redirect('/dashboard')
      //                   ->withErrors($validator)
      //                   ->withInput();
      //   }
		$title_txt    = (string) $request->input('title');
		$title_txt    = preg_replace('/\s+/', '_', $title_txt);
		$commands_txt = (string) $request -> input('commands');
		$filepath = "/mnt/hgfs/NetworkManager/snmp_files/".$title_txt;
		$file = fopen($filepath,'w');
		fwrite($file,$commands_txt);
		fclose($file);
		
		// exec("sed -i '0,/original/ s/original/new/' /mnt/hgfs/NetworkManager/snmp_files/$title_txt");
		// exec("sudo sed -i 's/original/new' /mnt/hgfs/NetworkManager/snmp_files/$title_txt");
	    // exec("echo '".$commands_txt. "' > /mnt/hgfs/NetworkManager/snmp_files/".$title_txt);

		// replace with //

		// $file_content=file_get_contents("/mnt/hgfs/NetworkManager/snmp_files/$title_txt");
		// $from = "original";
		// $from = '/'.preg_quote($from, '/').'/';
		// $file_content = preg_replace($from, "new", $file_content, 1);
		// $file = fopen("/mnt/hgfs/NetworkManager/snmp_files/$title_txt",'w');
		// fwrite($file, $file_content);
		// fclose($file);

		
    	$template = new Template;
		$template ->title    = $request -> input('title');
        $template ->user_id =  $user = Auth::user()->id;
    	$template->save();
    	return redirect('/dashboard/template')->with('success','success');
        }
    }



    // public function store(Request $request)
    // {
    //     return Validator::make($data, [
    //         'title' => 'required|max:191|unique:templates',
    //         'filepath' => 'required|max:191',
    //     ]);


    // }

    protected function get (Request $request)
    {
    	$title = $request->input('t');
    	$title = preg_replace('/\s+/', '_', $title);
    	exec("cat /mnt/hgfs/NetworkManager/snmp_files/$title",$output);
    	// $output = nl2br($output);
    			$x ="";
    		foreach ($output as $u)
    		{
    			$x = $x.$u."\r";
    		}
    	return $x;

    }


    protected function delete (Request $request)
    {

            DB::table('templates')->where('title','=',$request->input('delete'))->delete();
            $title = preg_replace('/\s+/', '_', $request->input('delete'));
            exec("rm /mnt/hgfs/NetworkManager/snmp_files/$title");
            return redirect('/dashboard/template')->with('successdelete','successdelete');
    }
}