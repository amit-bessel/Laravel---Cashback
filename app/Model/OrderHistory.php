<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'order_history';


    public function user_order()
    {
        return $this->belongsTo('App\Model\SiteUser','user_id');
    }

}
