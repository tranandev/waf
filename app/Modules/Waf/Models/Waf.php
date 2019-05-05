<?php

namespace App\Modules\Waf\Models;

use Illuminate\Database\Eloquent\Model;

class Waf extends Model
{
    //
    public $timestamps = false;
    protected $table = 'group_rule_status';
    protected $fillable = ['group_rule_id', 'group_website_id', 'status'];
}
