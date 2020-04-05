<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Product;

class Wishlist extends Model
{
	protected $guarded=[];
	public $timestamps = false;

    protected $table = 'wishlists';


    public function product_wishlist()
    {
        return $this->belongsTo('App\Model\Product','product_id');
    }

}
