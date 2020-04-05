<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Walletdetails extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'walletdetails';

     public function siteusers()
    {
        return $this->belongsTo('App\Model\SiteUser','siteusers_id');
    }

    
}
