<?php
namespace App\Modules\Rules\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use App\Modules\Rules\Models\Custom;
use App\Modules\GroupRules\Models\GroupRules;
use App\Modules\GroupWebsite\Models\Groupwebsite;
use App\Modules\Waf\Models\Waf;

class CustomController extends Controller{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
        $this->rule_add = ['rule'=>'required', 'description'=>'required', 'group_rule'=>'required'];
    }

        public function getDataTable() {
        $custom = Custom::all();
        $custom->load('grouprulecustom');
        return '{"data": '.json_encode($custom).'}';
    }

    public function addRule(Request $request) {
        try {
            $validate_error = $this->rule_validate($request, $this->rule_add);
            if ($validate_error) return $validate_error;
            $rule = $request->rule;
            $description = $request->description;
            $group_rule = $request->group_rule;
            $custom = new Custom;
            $custom->rule = $rule;
            $custom->description = $description;
            $custom->group_rule_id = $group_rule;
            $custom->save();
            $group_rule_name = GroupRules::find($custom->group_rule_id)->name;
            $rule_encode = base64_encode($rule);
            shell_exec("sudo python /var/log/core_waf/add_rule_custom.py ".$group_rule_name." ".$rule_encode." 2>&1");
            $exec = shell_exec("sudo apache2ctl configtest 2>&1");
            if (strpos($exec, 'Syntax OK') !== false) {
                return 1;
            } 
            return $exec;              
            }
             catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    public function updateRule(Request $request) {
        try {
            $validate_error = $this->rule_validate($request, $this->rule_add);
            if ($validate_error) return $validate_error;
            $id = $request->id;
            $rule = $request->rule;
            $description = $request->description;
            $group_rule = $request->group_rule;
            $group_rule_name = GroupRules::find($group_rule)->name;
            $old_rule = $request->old_rule;
            $old_description = $request->old_description;
            $old_group_rule_name = $request->old_group_rule;
            $custom = Custom::find($id);
            $custom->rule = $rule;
            $custom->description = $description;
            $custom->group_rule_id = $group_rule;
            $custom->save();
            $rule_encode = base64_encode($rule);
            $old_rule_encode = base64_encode($old_rule);
            $exec1 = shell_exec("sudo python /var/log/core_waf/update_rule_custom.py ".$group_rule_name." ".$old_group_rule_name." ".$old_rule_encode." ".$rule_encode." 2>&1");
            $exec = shell_exec("sudo apache2ctl configtest 2>&1");
            if (strpos($exec, 'Syntax OK') !== false) {
                return $exec;
            } 
            return $exec;            
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    public function deleteRule(Request $request) {
        try {
            $id = $request->id;
            $custom = Custom::find($id);
            $rule = $custom->rule;
            $group_rule_id = $custom->group_rule_id;
            $group_rule_name = GroupRules::find($group_rule_id)->name;
            $custom->delete();
            $rule_encode = base64_encode($rule);
            shell_exec("sudo python /var/log/core_waf/delete_rule_custom.py ".$group_rule_name." ".$rule_encode." 2>&1");
            return 1;            
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }


}
