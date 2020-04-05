<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Giftcard extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'giftcards';

    /*
	public function user_card_details()
    {
        return $this->hasMany('App\Model\UserCardDetail','user_id');
    }
    */

    // public function walletcashback(){
    //     return $this->hasMany('App\Model\Walletdetails','siteusers_id')->where("purpose_state","=","3")->where('status',1);
    // }
    public function giftcarddetails()
    {
        return $this->hasMany('App\Model\Giftcarddetail','giftcard_id');
    }

     public function giftcardimages()
    {
        return $this->hasMany('App\Model\Giftcardimage','giftcard_id');
    }
   
}
