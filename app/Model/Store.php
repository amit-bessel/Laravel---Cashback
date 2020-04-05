<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'stores';
	
}
