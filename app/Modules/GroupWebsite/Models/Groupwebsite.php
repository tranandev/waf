<?php

namespace App\Modules\GroupWebsite\Models;

use Illuminate\Database\Eloquent\Model;

class Groupwebsite extends Model
{
    //
    public $timestamps = true;
    protected $table = 'group_website';
    protected $fillable = ['name', 'description', 'status'];
}
