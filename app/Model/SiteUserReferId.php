<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SiteUserReferId extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'userreferid';

    /*
	public function user_card_details()
    {
        return $this->hasMany('App\Model\UserCardDetail','user_id');
    }
    */ 
   
}
