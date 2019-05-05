<?php

namespace App\Modules\Rules\Models;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    //
    public $timestamps = false;
    protected $table = 'url_deny';
    protected $fillable = ['id', 'host', 'url', 'description', 'group_rule_id'];
    public function groupruleurl() {
    	return $this->belongsTo("App\\Modules\\GroupRules\\Models\\GroupRules", "group_rule_id");
    }
}
