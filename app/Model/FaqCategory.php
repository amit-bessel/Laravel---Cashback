<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
	public $timestamps = false;

	/* If you need to set all columns as fillable, do this in the model: protected $guarded=[];*/

    protected $guarded=[];

    protected $table = 'faqcategories';

     public function faqs()
    {
        return $this->hasMany('App\Model\Faq','faqcategories_id');
    }

    public function faqrelation1()
    {
        return $this->hasMany('App\Model\FaqCategory','parentid');
    }
    public function faqrelation2()
    {
        return $this->belongsTo('App\Model\FaqCategory','parentid');
    }

    
}
?>