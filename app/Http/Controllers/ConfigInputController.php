<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigInputController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

   	protected function create (Request $request)
	{
				$filename  = preg_replace('/\s+/', '_', $request->input('st'));

                    exec("/mnt/hgfs/NetworkManager/snmp_files/template_forging $filename");
                    $file_tmp=file_get_contents("/mnt/hgfs/NetworkManager/snmp_files/$filename");
                    $file_tmp=preg_replace("/\([^)]+\)/","",$file_tmp);
                    $filepath = '/tftpboot/configPrototype/'.$filename;
                    $filepathprototype = $filepath.'_Prototype';
                    $file=fopen($filepathprototype, 'w');
                    fwrite($file, $file_tmp);
                    fclose($file);
                    exec("/mnt/hgfs/NetworkManager/snmp_files/template_forging2 $filepathprototype");
                    $file_content=file_get_contents('/mnt/hgfs/NetworkManager/snmp_files/list_uppercase') ;
                    $file_content2=file_get_contents('/mnt/hgfs/NetworkManager/snmp_files/list_parentheses') ;
                    $inputs = explode("\n",$file_content) ;
                    $inputs2 = explode("\n",$file_content2) ;
                    $z="";
                    $x=0;
                    
                foreach ($inputs as $input)
                {

                  if($input != "")
                  {
                      $z=$z.'<div class="form-group form-inline">
                      <label class="control-label">'.$inputs2[$x].'</label>';
                  }

                  if ($input =="IP")
                  {
                     $z=$z.'<div class="bigdots">
                             <input name="ip[]" type="number" min="0" max="255" minlength="1" maxlength="3" class="form-control" required>.<input name="ip[]" type="number" min="0" max="255" minlength="1" maxlength="3" class="form-control" required>.<input name="ip[]" type="number" min="0" max="255" minlength="1" maxlength="3" class="form-control" required>.<input name="ip[]" type="number" min="0" max="255" minlength="1" maxlength="3" class="form-control" required>
                          </div>
                      </div>';
                    }
                  else if ($input == "MASK")
                  {
                      $z=$z.'<div class="bigdots">
                             <input name="mask[]" type="number" min="0" max="255" minlength="1" maxlength="3" class="form-control" required>.<input name="mask[]" type="number" min="0" max="255" minlength="1" maxlength="3" class="form-control" required>.<input name="mask[]" type="number" min="0" max="255" minlength="1" maxlength="3" class="form-control" required>.<input name="mask[]" type="number" min="0" max="255" minlength="1" maxlength="3" class="form-control" required>
                          </div>
                      </div>';
                    }
                  else if ($input == "PASSWORD")
                  {
                      $z=$z.'<div class="bigdots">
                             <input name="password[]" type="password"  class="form-control" required>
                          </div>
                      </div>';
                    }

                  else if ($input != "")
                  {
                    $z=$z.'<input type="text" class="form-control" name="'.$input.'[]" required>
                    </div>';
                  }
                  $x++;

                 }
                  return $z;

	}
}
