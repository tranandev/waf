<?php
namespace App\Modules\Website\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use DB;
// use Input;
use App\Modules\Website\Models\Website;
use App\Modules\GroupWebsite\Models\Groupwebsite;

class WebsiteController extends Controller{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
        $this->rule_add = ['name'=>'required', 'ip'=>'required', 'listen_port'=>'required', 'group_website'=>'required'];
    }
    public function index(){
        $group_website = Groupwebsite::all();
        $return = ['group_website'=>$group_website];
        return view('Website::website', $return);
    }

    public function getWebsiteTableData(){
        $website = Website::all();
        $website->load('groupwebsite');
        // $website = Website::with(['groupwebsite']);
        return '{"data": '.json_encode($website).'}';
        // return '{"data": hunganh}';
    }

    public function addWebsite(Request $request){
        try {
            $validate_error = $this->rule_validate($request, $this->rule_add);
            if ($validate_error) return $validate_error;
            $name = $request->name;
            $ip = $request->ip;
            $listen_port = $request->listen_port;
            $group_website_id = $request->group_website;
            $ssl = $request->ssl;
            $website = new Website;
            $website->name = $name;
            $website->ip = $ip;
            $website->port_listen = $listen_port;
            $website->status = 0;
            $website->group_website_id = (int)$group_website_id;
            if ($ssl) {
                $key = Input::file('key')->move('private/', $name.'.key');
                $cert = Input::file('cert')->move('certs/', $name.'.pem');
                shell_exec("sudo cp /var/www/html/waf/public/private/".$name.".key /etc/ssl/private/");
                shell_exec("sudo cp /var/www/html/waf/public/certs/".$name.".pem /etc/ssl/certs/");
                $website->key = 1;
                $website->cert = 1;
                $website->ssl = 1;
                shell_exec("sudo python /var/log/core_waf/add_websites_ssl.py ". $website->name . " " . $website->group_website_id . " " . $website->ip . " " . $website->port_listen . " 2>&1");
                shell_exec("sudo ln -s /etc/apache2/sites-available/".$website->name."-ssl.conf /etc/apache2/sites-enabled/".$website->name."-ssl.conf 2>&1");
                shell_exec("sudo service apache2 reload 2>&1");
            } else {
            $website->key = 0;
            $website->cert = 0;
            $website->ssl = 0;
            shell_exec("sudo rm /var/www/html/waf/public/private/".$name.".key");
            shell_exec("sudo rm /var/www/html/waf/public/certs/".$name.".pem");
            shell_exec("sudo rm /etc/ssl/private/".$name.".key");
            shell_exec("sudo rm /etc/ssl/certs/".$name.".pem");
            shell_exec("sudo python /var/log/core_waf/add_websites.py ". $website->name . " " . $website->group_website_id . " " . $website->ip . " " . $website->port_listen . " 2>&1");
            shell_exec("sudo ln -s /etc/apache2/sites-available/".$website->name.".conf /etc/apache2/sites-enabled/".$website->name.".conf 2>&1");
            shell_exec("sudo service apache2 reload 2>&1");
            }
            $website->save();
            return 1;            
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    public function checkRule(Request $request) {
        $id = $request->id;
        $website = Website::find($id);
        $key = $website->key;
        $cert = $website->cert;
        if ($key == 1 && $cert == 1) return 1;
        else return 0;
    }

    public function updateWebsite(Request $request){
        try {
            $validate_error = $this->rule_validate($request, $this->rule_add);
            if ($validate_error) return $validate_error;
            $id = $request->id;
            $name = $request->name;
            $ip = $request->ip;
            $listen_port = $request->listen_port;
            $group_website_id = $request->group_website;
            $ssl = $request->ssl;
            $key_check = $request->key_check;
            $cert_check = $request->cert_check;
            $website = Website::find($id);
            $website->name = $name;
            $website->ip = $ip;
            $website->port_listen = $listen_port;
            $website->group_website_id = (int)$group_website_id;
            if ($ssl) {
                if ($key_check != 1 && $cert_check != 1) {
                $key = Input::file('key')->move('private/', $name.'.key');
                $cert = Input::file('cert')->move('certs/', $name.'.pem');
                shell_exec("sudo cp /var/www/html/waf/public/private/".$name.".key /etc/ssl/private/");
                shell_exec("sudo cp /var/www/html/waf/public/certs/".$name.".pem /etc/ssl/certs/");
                $website->key = 1;
                $website->cert = 1;
                $website->ssl = 1;
                shell_exec("sudo rm /etc/apache2/sites-available/".$name.".conf");
                shell_exec("sudo rm /etc/apache2/sites-enabled/".$name.".conf");
                shell_exec("sudo python /var/log/core_waf/add_websites_ssl.py ". $website->name . " " . $website->group_website_id . " " . $website->ip . " " . $website->port_listen . " 2>&1");                
                shell_exec("sudo ln -s /etc/apache2/sites-available/".$website->name."-ssl.conf /etc/apache2/sites-enabled/".$website->name."-ssl.conf 2>&1");
                shell_exec("sudo service apache2 reload 2>&1");
                }
                shell_exec("sudo python /var/log/core_waf/add_websites_ssl.py ". $website->name . " " . $website->group_website_id . " " . $website->ip . " " . $website->port_listen . " 2>&1");                
                shell_exec("sudo ln -s /etc/apache2/sites-available/".$website->name."-ssl.conf /etc/apache2/sites-enabled/".$website->name."-ssl.conf 2>&1");
                shell_exec("sudo service apache2 reload 2>&1");
            } else {
            $website->key = 0;
            $website->cert = 0;
            $website->ssl = 0;
            shell_exec("sudo rm /var/www/html/waf/public/private/".$name.".key");
            shell_exec("sudo rm /var/www/html/waf/public/certs/".$name.".pem");
            shell_exec("sudo rm /etc/ssl/private/".$name.".key");
            shell_exec("sudo rm /etc/ssl/certs/".$name.".pem");
            shell_exec("sudo rm /etc/apache2/sites-available/".$name."-ssl.conf");
            shell_exec("sudo rm /etc/apache2/sites-enabled/".$name."-ssl.conf");
            shell_exec("sudo python /var/log/core_waf/add_websites.py ". $website->name . " " . $website->group_website_id . " " . $website->ip . " " . $website->port_listen . " 2>&1");
            shell_exec("sudo ln -s /etc/apache2/sites-available/".$website->name.".conf /etc/apache2/sites-enabled/".$website->name.".conf 2>&1");
            shell_exec("sudo service apache2 reload 2>&1");
            }
            $website->save();
            return 1;            
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    public function deleteWebsite(Request $request){
        try {
            $id = $request->id;
            $website = Website::find($id);
            $name = $website->name;
            if ($website->ssl == 1) {
                shell_exec("sudo rm /var/www/html/waf/public/private/".$name.".key");
                shell_exec("sudo rm /var/www/html/waf/public/certs/".$name.".pem");
                shell_exec("sudo rm /etc/ssl/private/".$name.".key");
                shell_exec("sudo rm /etc/ssl/certs/".$name.".pem");
                shell_exec("sudo rm /etc/apache2/sites-available/".$website->name."-ssl.conf");
                shell_exec("sudo rm /etc/apache2/sites-enabled/".$website->name."-ssl.conf");
            } else {
                shell_exec("sudo rm /etc/apache2/sites-available/".$website->name.".conf");
                shell_exec("sudo rm /etc/apache2/sites-enabled/".$website->name.".conf");
            }
            $website->delete();
            return 1;            
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

}
