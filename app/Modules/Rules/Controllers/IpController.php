<?php
namespace App\Modules\Rules\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use App\Modules\Rules\Models\Ip;
use App\Modules\GroupRules\Models\GroupRules;
use App\Modules\GroupWebsite\Models\Groupwebsite;
use App\Modules\Waf\Models\Waf;

class IpController extends Controller{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
        $this->rule_add = ['ip'=>'required|unique:ip_deny', 'description'=>'required', 'group_rule'=>'required'];
        $this->rule_update = ['ip'=>'required', 'description'=>'required', 'group_rule'=>'required'];
    }

    public function getDataTable() {
        $ip = Ip::all();
        $ip->load('groupruleip');
        return '{"data": '.json_encode($ip).'}';
    }

    public function addRule(Request $request) {
        try {
            $validate_error = $this->rule_validate($request, $this->rule_add);
            if ($validate_error) return $validate_error;
            $ip = $request->ip;
            $description = $request->description;
            $group_rule = $request->group_rule;
            $IP = new Ip;
            $IP->ip = $ip;
            $IP->description = $description;
            $IP->group_rule_id = $group_rule;
            $IP->save();
            $group_rule_name = GroupRules::find($IP->group_rule_id);
            shell_exec("sudo python /var/log/core_waf/add_rules.py ".$group_rule_name->name." ".$IP->ip." ".$IP->id." 2>&1");
            return 1;            
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    public function updateRule(Request $request) {
        try {
            $validate_error = $this->rule_validate($request, $this->rule_update);
            if ($validate_error) return $validate_error;
            $id = $request->id;
            $ip = $request->ip;
            $description = $request->description;
            $group_rule = $request->group_rule;
            $IP = Ip::find($id);
            $IP->ip = $ip;
            $IP->description = $description;
            $IP->group_rule_id = $group_rule;
            $IP->save();
            $group_rule_name = GroupRules::find($IP->group_rule_id);
            shell_exec("sudo python /var/log/core_waf/update_rules.py ".$group_rule_name->name." ".$IP->ip." ".$IP->id." 2>&1");
            return 1;            
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    public function deleteRule(Request $request) {
        try {
            $id = $request->id;
            $IP = Ip::find($id);
            $group_rule_name = GroupRules::find($IP->group_rule_id);
            $IP->delete();
            shell_exec("sudo python /var/log/core_waf/delete_rules.py ".$group_rule_name->name." ".$id." 2>&1");
            return 1;            
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

}
