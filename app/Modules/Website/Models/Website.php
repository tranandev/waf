<?php

namespace App\Modules\Website\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    //
    public $timestamps = true;
    protected $table = 'website';
    protected $fillable = ['name', 'ip', 'port_listen', 'ssl', 'key', 'cert', 'group_website_id'];

    public function groupwebsite() {
    	return $this->belongsTo("App\\Modules\\GroupWebsite\\Models\\Groupwebsite", "group_website_id");
    }

}
