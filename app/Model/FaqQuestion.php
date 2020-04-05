<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FaqQuestion extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'faq_questions';


}
?>