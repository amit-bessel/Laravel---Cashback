<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Productnew extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'newproducts';

	public function product_category(){
    	return $this->belongsTo('App\Model\Category', 'category_id');
    }
}

