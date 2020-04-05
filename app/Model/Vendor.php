<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'all_vendors';

    public function get_store_category(){
		return $this->belongsToMany('App\Model\Store', 'category_stores','vendor_id','category_id');
	}
    
}

