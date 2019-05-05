<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $rule_add;
    public $rule_update;
    function rule_validate(Request $request, $rule) {
        $vali = Validator::make($request->all(), $rule);
        if ($vali->fails()) {
            $messages = $vali->errors();
            $a = '';
            foreach ($messages->all() as $message) {
                $a = $a.' '.$message;
            }
            return $a;
        }
        return ;
    }
}
