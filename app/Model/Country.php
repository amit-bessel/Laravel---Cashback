<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'countries';

    public function children()
	{
	    return $this->hasMany(self::class, 'parent_id');
	}
}
