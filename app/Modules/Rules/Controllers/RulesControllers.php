<?php
namespace App\Modules\Rules\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;
use App\Modules\Rules\Models\Rules;
use App\Modules\GroupRules\Models\GroupRules;
use App\Modules\GroupWebsite\Models\Groupwebsite;
use App\Modules\Waf\Models\Waf;

class RulesControllers extends Controller{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }
    public function index(){
        $group_rule = GroupRules::where('id', '>', 14)->get();
        $return = ['group_rule'=>$group_rule];
        return view('Rules::rule', $return);
    }

}
