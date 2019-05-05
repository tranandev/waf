<?php
namespace App\Modules\Monitor\Controllers;

use Validator;
use App\Http\Controllers\Controller;
use DB;
// use Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\QueryException;
use App\Modules\GroupRules\Models\GroupRules;
use App\Modules\GroupWebsite\Models\Groupwebsite;
use App\Modules\Waf\Models\Waf;
use App\Modules\Monitor\Models\Monitor;

class MonitorController extends Controller{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
        // $this->rule_add = ['name'=>'required|unique:group_rule', 'description'=>'required'];
        // $this->rule_update = ['name'=>'required', 'description'=>'required'];
    }
    public function index(){
        return view('Monitor::monitor');
    }

    public function getMonitorDataTable(){
        $monitor = Monitor::all();
        $monitor->load('groupwebsite');
        $monitor->load('website');
        $monitor->load('grouprule');
        return '{"data": '.json_encode($monitor).'}';
    }

    public function getIP() {
        $period = Input::get('period');
        $result = DB::select("select count(*) as y, ip from monitor where UNIX_TIMESTAMP() - time <= ".$period." group by ip order by y desc limit 10");
        return json_encode($result);
    }

    public function getAttack() {
        $period = Input::get('period');
        $result = DB::table('monitor')->join('group_website', 'group_website.id', 'monitor.group_website')->join('website', 'website.id', 'monitor.website')->join('group_rule', 'group_rule.id', 'monitor.group_rule')->whereRaw(time()." - time < ".$period)->groupBy('group_rule.name')->orderBy('group_rule.name', 'desc')->limit(10)->select(DB::raw('count(*) as y'), 'group_rule.name as attack')->get();
        return json_encode($result);
    }
}
