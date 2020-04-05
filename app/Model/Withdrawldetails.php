<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Withdrawldetails extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'withdrawldetails';

    
	public function siteuser()
    {
        return $this->belongsTo('App\Model\SiteUser','siteusers_id');
    }
    
   
}
