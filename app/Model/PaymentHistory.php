<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'payment_histories';

    public function bussiness_owner_details()
    {
        return $this->belongsTo('App\Model\SiteUser','user_id')->where("user_type","=","1")->select(array('id','name','email'));
    }
	public function customer_details()
    {
        return $this->belongsTo('App\Model\SiteUser','user_id')->where("user_type","=","2")->select(array('id','name','email'));
    }
	public function user_details()
    {
        return $this->belongsTo('App\Model\SiteUser','user_id')->select(array('id','name','last_name','email'));
    }

}
