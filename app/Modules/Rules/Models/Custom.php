<?php

namespace App\Modules\Rules\Models;

use Illuminate\Database\Eloquent\Model;

class Custom extends Model
{
    //
    public $timestamps = false;
    protected $table = 'custom_rule';
    protected $fillable = ['id', 'rule', 'description', 'group_rule_id'];
    public function grouprulecustom() {
    	return $this->belongsTo("App\\Modules\\GroupRules\\Models\\GroupRules", "group_rule_id");
    }
}
