<?php
namespace App\Modules\GroupRules\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use App\Modules\GroupRules\Models\GroupRules;
use App\Modules\GroupWebsite\Models\Groupwebsite;
use App\Modules\Waf\Models\Waf;
use Illuminate\Database\QueryException;

class GroupRulesController extends Controller{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
        $this->rule_add = ['name'=>'required|unique:group_rule', 'description'=>'required'];
        $this->rule_update = ['name'=>'required', 'description'=>'required'];
    }
    public function index(){
        return view('GroupRules::grouprule');
    }

    public function getDataTable(){
        $group_rule = GroupRules::where('id', '>', 14)->get();
        return '{"data": '.json_encode($group_rule).'}';
    }

    public function addGroupRule(Request $request) {
        try {   
        $validate_error = $this->rule_validate($request, $this->rule_add);
        if ($validate_error) return $validate_error;        
        $name = $request->name;
        $des = $request->description;
        $group_rule = new GroupRules;
        $group_rule->name = $name;
        $group_rule->description = $des;
        $group_rule->tag = $name;
        $group_rule->save();
        $group_website = Groupwebsite::all();
        $group_website_num = Groupwebsite::all()->count();
        $new_rule_id = $group_rule->id;
        for ($i = 0; $i < $group_website_num; $i++) {
            $waf = new Waf;
            $waf->group_rule_id = $new_rule_id;
            $waf->group_website_id = $group_website[$i]->id;
            $waf->status = 0;
            $waf->save();
        }
        shell_exec("sudo python /var/log/core_waf/add_group_rule.py ".$name);
        return 1;
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }


    public function updateGroupRule(Request $request) {
        try {
            $validate_error = $this->rule_validate($request, $this->rule_update);
            if ($validate_error) return $validate_error;
            $id = $request->id;
            $name = $request->name;
            $description = $request->description;
            $group_rule = GroupRules::find($id);
            $old_name = $group_rule->name;
            $group_rule->name = $name;
            $group_rule->description = $description;
            $group_rule->tag = $name;
            $group_rule->save();
            shell_exec("sudo mv /etc/modsecurity/rules/custom/".$old_name.".conf /etc/modsecurity/rules/custom/".$name.".conf");
            shell_exec("sudo python /var/log/core_waf/update_group_rules.py ".$old_name." ".$name);
            return 1;            
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    public function deleteGroupRule(Request $request) {
        try {
            $id = $request->id;
            $group_rule = GroupRules::find($id);
            $name = $group_rule->name;
            $group_rule_delete = Waf::where('group_rule_id', $id)->get();
            foreach ($group_rule_delete as $element){
                $element->delete();
            }
            $exec = shell_exec("sudo rm /etc/modsecurity/rules/custom/".$name.".conf");
            shell_exec("sudo python /var/log/core_waf/delete_group_rule.py ".$name);
            $group_rule->delete();
            return 1;
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

}
