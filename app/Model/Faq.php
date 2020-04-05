<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable=[
        'question',
        'answer',
        'faqcategories_id'
       ];

    protected $table = 'faqs';

    public function faqCategory()
    {
        return $this->belongsTo('App\Model\FaqCategory','faqcategories_id');
    }
}
