<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Userrefer extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'userreferdetails';

    public function userreferlink()
    {
        return $this->belongsTo('App\Model\SiteUser','referby');
    }
    public function userreferlink1()
    {
        return $this->belongsTo('App\Model\SiteUser','referto');
    }
	
}
