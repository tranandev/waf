<?php
namespace App\Modules\Rules\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use App\Modules\Rules\Models\Url;
use App\Modules\GroupRules\Models\GroupRules;
use App\Modules\GroupWebsite\Models\Groupwebsite;
use App\Modules\Waf\Models\Waf;

class UrlController extends Controller{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
        $this->rule_add = ['url'=>'required', 'description'=>'required', 'group_rule'=>'required'];
    }

    public function getDataTable() {
        $url = Url::all();
        $url->load('groupruleurl');
        return '{"data": '.json_encode($url).'}';
    }

    public function addRule(Request $request) {
        try {
            $validate_error = $this->rule_validate($request, $this->rule_add);
            if ($validate_error) return $validate_error;
            $host = $request->host;
            if ($host == '0') $host = '';
            $url = $request->url;
            $description = $request->description;
            $group_rule = $request->group_rule;
            $URL = new Url;
            $URL->host = $host;
            $URL->url = $url;
            $URL->description = $description;
            $URL->group_rule_id = $group_rule;
            $URL->save();
            $group_rule_name = GroupRules::find($URL->group_rule_id);
            if ($URL->host == '') {
                shell_exec("sudo python /var/log/core_waf/add_rule_url.py ".$group_rule_name->name." ".$URL->id." ".$URL->url." 2>&1");
                shell_exec("sudo service apache2 reload 2>&1");               
            } else {
                shell_exec("sudo python /var/log/core_waf/add_rule_url.py ".$group_rule_name->name." ".$URL->id." ".$URL->url." ".$URL->host." 2>&1");                                
                shell_exec("sudo service apache2 reload 2>&1");
            }
            return 1;            
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    public function updateRule(Request $request) {
        try {
            $validate_error = $this->rule_validate($request, $this->rule_add);
            if ($validate_error) return $validate_error;
            $id = $request->id;
            $host = $request->host;
            if ($host == '0') $host = '';
            $url = $request->url;
            $description = $request->description;
            $group_rule = $request->group_rule;
            $group_rule_name = GroupRules::find($group_rule)->name;
            $old_host = $request->old_host;
            $old_url = $request->old_url;
            $old_description = $request->old_description;
            $old_group_rule_name = $request->old_group_rule;
            $URL = Url::find($id);
            $URL->host = $host;
            $URL->url = $url;
            $URL->description = $description;
            $URL->group_rule_id = $group_rule;
            $URL->save();
            if (strpos($old_url, '?') !== false) {
                $old_url = str_replace("?", "\\\\?", $old_url);
            }
            if ($host == '') $host = '0';
            shell_exec("sudo python /var/log/core_waf/update_rule_url.py ".$group_rule_name." ".$old_group_rule_name." ".$url." ".$old_url." ".$description." ".$old_description." ".$host." ".$old_host." ".$id." 2>&1");
            return 1;            
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    public function deleteRule(Request $request) {
        try {
            $id = $request->id;
            $URL = Url::find($id);
            $host = $URL->host;
            if ($host == '') $host = '0';
            $url = $URL->url;
            $group_rule_id = $URL->group_rule_id;
            $group_rule_name = GroupRules::find($group_rule_id)->name;
            $URL->delete();
            if (strpos($url, '?') !== false) {
                $url = str_replace("?", "\\\\?", $url);
            }
            shell_exec("sudo python /var/log/core_waf/delete_rule_url.py ".$group_rule_name." ".$id." ".$url." ".$host." 2>&1");
            return 1;            
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

}
