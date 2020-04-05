<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'sub_categories';
}

