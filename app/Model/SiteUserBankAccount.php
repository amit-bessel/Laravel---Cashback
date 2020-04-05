<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SiteUserBankAccount extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'site_user_bank_accounts';

   /* public function user_details()
    {
        return $this->belongsTo('App\Model\SiteUser','user_id')->select(array('id','name','email'));
    }*/

}
