<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserLoginDetail extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'user_login_details';
}
