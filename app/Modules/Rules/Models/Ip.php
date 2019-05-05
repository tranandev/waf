<?php

namespace App\Modules\Rules\Models;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    //
    public $timestamps = false;
    protected $table = 'ip_deny';
    protected $fillable = ['id', 'ip', 'description', 'group_rule_id'];
    public function groupruleip() {
    	return $this->belongsTo("App\\Modules\\GroupRules\\Models\\GroupRules", "group_rule_id");
    }
}
