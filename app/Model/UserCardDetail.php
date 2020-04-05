<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserCardDetail extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'user_card_details';

    public function user_details()
    {
        return $this->belongsTo('App\Model\SiteUser','user_id')->select(array('id','name','email'));
    }

}
