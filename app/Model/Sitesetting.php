<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Sitesetting extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'sitesettings'; 
}
