<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Emailnotification extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'emailnotifications';

    /*
	public function user_card_details()
    {
        return $this->hasMany('App\Model\UserCardDetail','user_id');
    }
    */

   
}
