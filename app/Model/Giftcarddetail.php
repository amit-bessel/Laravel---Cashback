<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Giftcarddetail extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'giftcarddetails';

    
    public function giftcard()
    {
        return $this->belongsTo('App\Model\Giftcard','giftcard_id');
    }
    
   
}
