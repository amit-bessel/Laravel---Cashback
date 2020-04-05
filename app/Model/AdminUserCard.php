<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminUserCard extends Model
{
	public $timestamps = false;
    protected $guarded=[];

    protected $table = 'admin_user_cards';

}
