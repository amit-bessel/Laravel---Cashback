<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DataFeed extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'cj_datafeed_name';

    public function children()
	{
	    return $this->hasMany(self::class, 'parent_id');
	}
}
