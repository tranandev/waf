<?php
namespace App\Modules\Security\Controllers;
use Request;
// use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use App\Modules\Rules\Models\Custom;
use App\Modules\GroupRules\Models\GroupRules;
use App\Modules\Website\Models\Website;
use App\Modules\Waf\Models\Waf;

class SecurityController extends Controller{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    public function index() {
        $website = Website::all();
        $return = ['website'=>$website];
        return view('Security::security', $return);
    }

    public function checkSecure() {
        $ip = Input::get('ip');
        if ($ip != 'undefined') {
            $url = 'http://'.$ip.'/check';
            // $data = array('key1' => 'value1', 'key2' => 'value2');
            $options = array(
                  'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'GET',
                    // 'content' => http_build_query($data),
                  ),
                );
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            if ($result != false) {
                echo $result;
            }
        } else {
           $output = json_decode(shell_exec("sudo python /var/log/core_waf/check_security/check_secure_server.py check 2>&1"));
        // $output = shell_exec("ls /home/anhthc/");
        // print_r($output);
        // echo "Current user is: " . get_current_user();
            // return json_encode($output);
            $a = array('0');
            $b = array('0');
            for ($x = 0; $x < count($output); $x++){
                if ($output[$x][0] != 0){
                    array_push($a, $output[$x][0]);
                    array_push($b, $output[$x]);
                }
            }
            array_sort($b, 0, SORT_DESC);
            sort($a);
            unset($a[0]);
            unset($b[0]);
            $error = DB::table('information')->whereIn('id', $a)->get();
            // return json_encode($b);
            for ($i = 0; $i < count($error); $i++){
                $error[$i]->l_error = $b[$i + 1];
            }
            return json_encode($error);
        }
    }

    public function fixError(request $request) {
        try {
            $ip = Input::get('ip');
            $token = Input::get('token');
            if ($ip != 'undefined') {
                $data = Request::get('id');
                $url = 'http://'.$ip.'/fix';
                $options = array(
                      'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\nX-CSRF-TOKEN: ".$token."\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query(array($data)),
                      ),
                    );
                $context  = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                if ($result != false) {
                    echo $result;
                }
                // return http_build_query($data);
                // return $data;
            } else {
                $checked = false;
                $data = Request::get('id');
                $array = array(50, 28, 25, 10, 1, 7, 36);
                for ($x = 0; $x < count($array); $x++){
                    if (in_array($array[$x], $data)){
                        $checked = true;
                        break;
                    }
                }
                $info = DB::table('information')->whereIn('id', $data)->get();
                for ($i = 0; $i < count($info); $i++){
                    $OUT = shell_exec("sudo runp /var/log/core_waf/check_security/".$info[$i]->group_c."/".$info[$i]->name_c.".py fix_o 2>&1");
                }
                if ($checked){
                    shell_exec("sudo /sbin/service apache2 restart");
                }
                return 1;              
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function fixErrorAll() {
        try {
            $ip = Input::get('ip');
            if ($ip != 'undefined') {
                $url = 'http://'.$ip.'/fix-all';
                // $data = array('key1' => 'value1', 'key2' => 'value2');
                $options = array(
                      'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'GET',
                        // 'content' => http_build_query($data),
                      ),
                    );
                $context  = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                if ($result != false) {
                    echo $result;
                }
            } else {
                json_encode(shell_exec("sudo python /var/log/core_waf/check_security/check_secure_server.py fix"));
                return 1;                            
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
