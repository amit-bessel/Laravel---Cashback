<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'test1';

	public function product_category(){
    	return $this->belongsTo('App\Model\Category', 'category_id');
    }

    public function product_brand(){
    	return $this->belongsTo('App\Model\Brand', 'brand_id');
    }

    public function product_vendor(){
    	return $this->belongsTo('App\Model\Vendor', 'advertiser-id');
    }
}

