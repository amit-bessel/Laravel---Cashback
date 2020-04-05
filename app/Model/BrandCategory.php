<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BrandCategory extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'brand_categories';
	
	
	public function get_brand_category(){
		return $this->belongsTo('App\Model\Category','category_id');
	}


}
