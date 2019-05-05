<?php

namespace App\Modules\Monitor\Models;

use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    //
    public $timestamps = false;
    protected $table = 'monitor';
    protected $fillable = ['id', 'ip', 'time', 'country', 'group_website', 'website', 'group_rule', 'request_header', 'match_info'];
    public function groupwebsite() {
    	return $this->belongsTo("App\\Modules\\GroupWebsite\\Models\\Groupwebsite", "group_website");
    }

    public function website() {
    	return $this->belongsTo("App\\Modules\\Website\\Models\\Website", "website");
    }

    public function grouprule() {
    	return $this->belongsTo("App\\Modules\\GroupRules\\Models\\GroupRules", "group_rule");
    }
}
