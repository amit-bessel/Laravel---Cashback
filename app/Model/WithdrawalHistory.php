<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WithdrawalHistory extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'withdrawal_history';


    public function user_order()
    {
        return $this->belongsTo('App\Model\SiteUser','user_id');
    }

}
