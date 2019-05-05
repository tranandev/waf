<?php

namespace App\Modules\GroupRules\Models;

use Illuminate\Database\Eloquent\Model;

class GroupRules extends Model
{
    //
    public $timestamps = false;
    protected $table = 'group_rule';
    protected $fillable = ['id', 'name', 'description'];
}
