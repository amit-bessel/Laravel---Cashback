<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Emailnotification_Siteuser extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'emailnotifications_siteusers';

  
	public function siteusers()
    {
        return $this->belongsTo('App\Model\SiteUser','siteusers_id');
    }
    public function emailnotifications()
    {
        return $this->belongsTo('App\Model\Emailnotification','emailnotifications_id')->where("status","=",1);
    }

   
}
