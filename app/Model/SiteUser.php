<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SiteUser extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'siteusers';

    /*
	public function user_card_details()
    {
        return $this->hasMany('App\Model\UserCardDetail','user_id');
    }
    */

    public function walletcashback(){
        return $this->hasMany('App\Model\Walletdetails','siteusers_id')->where("purpose_state","=","3")->where('status',1);
    }
    public function userrefertolink()
    {
        return $this->hasMany('App\Model\Userrefer','referby');
    }
    public function userreferidrelation()
    {
        return $this->hasMany('App\Model\SiteUserReferId','siteuser_id');
    }

    public function tangoorder()
    {
        return $this->hasMany('App\Model\Tangoorder','siteusers_id');
    }

    public function invitefriend()
    {
        return $this->hasMany('App\Model\Invitefriend','siteusers_id');
    }
   
}
