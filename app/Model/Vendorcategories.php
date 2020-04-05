<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Vendorcategories extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'vendorcategories';

    /*
	public function user_card_details()
    {
        return $this->hasMany('App\Model\UserCardDetail','user_id');
    }
    */

    

    public function vendordetails()
    {
        return $this->hasMany('App\Model\vendordetails','vendorcategories_id');
    }

   
   
}
