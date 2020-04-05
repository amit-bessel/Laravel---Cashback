<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Usersmodules extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'users_modules';

    /*
	public function user_card_details()
    {
        return $this->hasMany('App\Model\UserCardDetail','user_id');
    }
    */

    public function modules()
    {
        return $this->belongsTo('App\Model\Module','modules_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Model\User','users_id');
    }

   
   
}
