<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Topbanner extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'top_banner';
}

