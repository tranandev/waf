<?php
namespace App\Modules\GroupWebsite\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Modules\GroupWebsite\Models\Groupwebsite;
use App\Modules\Website\Models\Website;
use App\Modules\Waf\Models\Waf;
use App\Modules\GroupRules\Models\GroupRules;
use Illuminate\Database\QueryException;

class GroupWebsiteController extends Controller{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
        $this->rule_add = ['name'=>'required|unique:group_website', 'description'=>'required'];
        $this->rule_update = ['name'=>'required', 'description'=>'required'];
    }
    public function index(){
        return view('GroupWebsite::groupwebsite');
    }

    public function getTableData(){
        $groupwebsite = Groupwebsite::all();
        return '{"data": '.json_encode($groupwebsite).'}';
    }

    public function addGroup(Request $request) {
        try {
            $validate_error = $this->rule_validate($request, $this->rule_add);
            if ($validate_error) return $validate_error;
            $name = $request->name;
            $des = $request->description;
            $group_website = new Groupwebsite;
            $group_website->name = $name;
            $group_website->description = $des;
            $group_website->status = 0;
            $group_website->save();

            $group_rule_num = GroupRules::all()->count();
            $group_rule = GroupRules::where('id', '>', 14)->get();
            for ($i=1;$i<15;$i++){
                $group_rule_waf = new Waf;
                $group_rule_waf->group_rule_id = $i;
                $group_rule_waf->group_website_id = $group_website->id;
                $group_rule_waf->status = 1;
                $group_rule_waf->save();
            }

            for ($i = 0; $i < ($group_rule_num - 14); $i++ ) {
                $group_rule_waf = new Waf;
                $group_rule_waf->group_rule_id = $group_rule[$i]->id;
                $group_rule_waf->group_website_id = $group_website->id;
                $group_rule_waf->status = 0;
                $group_rule_waf->save();
            }

            $id = $group_website->id;
            shell_exec("sudo python /var/log/core_waf/add_group_websites.py ".$id." 2>&1");
            return 1;           
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    public function updateGroup(Request $request) {
        try {
            $validate_error = $this->rule_validate($request, $this->rule_update);
            if ($validate_error) return $validate_error;
            $id = $request->id;
            $name = $request->name;
            $description = $request->description;
            $group_website = Groupwebsite::find($id);
            $group_website->name = $name;
            $group_website->description = $description;
            $group_website->save();
            return 1;        
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    public function deleteGroup(Request $request) {
        try {
            $id = $request->id;
            $website = Website::where('group_website_id', $id)->get();
            foreach ($website as $element){
                $element->delete();
                shell_exec("sudo rm /etc/apache2/sites-available/".$element->name.".conf");
                shell_exec("sudo rm /etc/apache2/sites-enabled/".$element->name.".conf");
                shell_exec("sudo rm /etc/apache2/sites-available/".$element->name."-ssl.conf");
                shell_exec("sudo rm /etc/apache2/sites-enabled/".$element->name."-ssl.conf");
            }
            $group_rule = Waf::where('group_website_id', $id)->get();
            foreach ($group_rule as $element){
                $element->delete();
            }
            shell_exec("sudo rm /etc/modsecurity/group/group_websites_waf_".$id.".conf");
            $group_website = Groupwebsite::find($id);
            $group_website->delete();
            return 1;            
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }
}