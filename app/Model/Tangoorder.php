<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tangoorder extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'tangoorders';

    public function siteuser()
    {
        return $this->belongsTo('App\Model\SiteUser','siteusers_id');
    } 
}