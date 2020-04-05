<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'categories';

    public function children()
	{
	    return $this->hasMany(self::class, 'parent_id');
	}

	public function parent()
	{
	    return $this->belongsTo(self::class, 'parent_id');
	}
}
