<?php
namespace App\Modules\Dashboard\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class DashboardController extends Controller{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }
    public function index(){
        $cpu = shell_exec("sudo cat /proc/cpuinfo | grep 'model name' | uniq 2>&1");
        // $cpu = shell_exec("sudo cat /proc/cpuinfo 2>&1");
        $cpu = explode(":" , $cpu)[1];
        // echo $cpu;

        $mem = shell_exec("sudo cat /proc/meminfo | grep 'MemTotal' | uniq");
        $mem = explode(":" , $mem)[1];

        $hdd = shell_exec("df -h | grep '/dev/' 2>&1");
        // shell_exec("sudo python /var/log/corebif/Main.py NAT add $nat1 2>&1");
        preg_match('/[0-9,.]{1,}G/', $hdd, $matches);
        $hdd = $matches[0];


        $return = ['cpu'=>$cpu, 'mem'=>$mem, "hdd"=>$hdd];
        return view('Dashboard::dashboard', $return);
    }

    public function get_resource(){
        header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	// $old_resource = DB::table('resource_monitor')->orderBY('id', 'DESC')->limit(100)->get();
	$resource = DB::table('resource_monitor')->orderBy('id', 'DESC')->first();
	echo "data: ".json_encode((array)$resource)."\n\n";
	// echo "old_data: ".json_encode((array)$old_resource)."\n";
	flush();
    }

    public function get_old_resource(){
        //header('Content-Type: text/event-stream');
        //header('Cache-Control: no-cache');
        $old_resource = DB::table('resource_monitor')->orderBY('id', 'DESC')->limit(100)->get();
        //$resource = DB::table('resource_monitor')->orderBy('id', 'DESC')->first();
        //echo "data: ".json_encode((array)$resource)."\n\n";
        return json_encode($old_resource);
        //flush();
    }


}
