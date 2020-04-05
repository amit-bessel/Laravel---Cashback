<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Vendordetails extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'vendordetails';

    /*
	public function user_card_details()
    {
        return $this->hasMany('App\Model\UserCardDetail','user_id');
    }
    */

    public function vendorcategories()
    {
        return $this->belongsTo('App\Model\Vendorcategories','vendorcategories_id');
    }
    
   
}
