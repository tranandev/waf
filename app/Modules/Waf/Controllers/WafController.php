<?php
namespace App\Modules\Waf\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use App\Modules\Waf\Models\Waf;
use App\Modules\GroupRules\Models\GroupRules;
use App\Modules\GroupWebsite\Models\Groupwebsite;

class WafController extends Controller{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }
    public function index(){
        return view('Waf::waf');
    }

    public function getGroupRule(){
        $groupWebSiteId = Input::get('id');
        $group_rule = DB::table('group_rule')->join('group_rule_status','group_rule.id', '=','group_rule_status.group_rule_id')->where('group_website_id', '=', $groupWebSiteId)->select('group_rule_status.id as id', 'group_rule_status.group_rule_id as rule_id', 'group_rule_status.group_website_id as group_website_id', 'group_rule_status.status as rule_status', 'group_rule.name as rule_name', 'group_rule.description as rule_description')->get();
        // $group_rule = DB::table('group_rule')->join('group_rule_status', 'group_rule.id', '=', 'group_rule_status.')->get();
        return $group_rule;
    }

    public function changeGroupWebsiteStatus(Request $request) {
        $id = $request->id;
        $group_website = Groupwebsite::find($id);
        if ($group_website->status == 0 ) {
            $group_website->status = 1;
            shell_exec("sudo python /var/log/core_waf/change_group_website_status.py ".$id." 1 2>&1");
        }else {
            $group_website->status = 0;
            shell_exec("sudo python /var/log/core_waf/change_group_website_status.py ".$id." 0 2>&1");         
        }
        $group_website->save();
        return 0;
    }

    public function changeRuleStatus(Request $request) {
        $id = $request->id;
        $group_rule = Waf::find($id);
        $group_website_id = $group_rule->group_website_id;
        $group_rule_id = $group_rule->group_rule_id;
        $tag_name = GroupRules::find($group_rule_id)->tag;
        // $group_website_name = Groupwebsite::find($group_website_id);
        if ($group_rule->status == 0 ) {
            $group_rule->status = 1;
            shell_exec("sudo python /var/log/core_waf/change_group_rules_status.py ".$group_website_id." ".$tag_name." 1 2>&1");            
        } 
        else {
            $group_rule->status = 0;
            shell_exec("sudo python /var/log/core_waf/change_group_rules_status.py ".$group_website_id." ".$tag_name." 0 2>&1");            
        } 
        $group_rule->save();
        return 1;
    }

    public function Restart() {
        $exec = shell_exec("sudo python /var/log/core_waf/restart.py");
        return $exec;
    }
}
