<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoryStore extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'category_stores';
}
