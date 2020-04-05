<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invitefriend extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'invitefriends';

    /*
	public function user_card_details()
    {
        return $this->hasMany('App\Model\UserCardDetail','user_id');
    }
    */ 
	  
	public function siteuser()
    {
        return $this->belongsTo('App\Model\SiteUser','siteusers_id');
    } 
   
}