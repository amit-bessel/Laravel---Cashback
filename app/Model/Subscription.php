<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'subscriptions';
	
	public function user_details()
    {
        return $this->belongsTo('App\Model\SiteUser','user_id');
    }

    public function business_details()
    {
        return $this->belongsTo('App\Model\BusinessList','business_id');
    }
	public function branch_details()
    {
        return $this->belongsTo('App\Model\BusinessBranch','branch_id');
    }
}
