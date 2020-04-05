<?php

namespace App\Model;
 

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Http\Controllers\Auth\AuthController;

class TicketsReply extends Model
{
    public    $timestamps = false;
    protected $guarded    = [];
    protected $table      = 'ticket_replies';

    public function replybyuser()
    {
        return $this->belongsTo('App\Model\SiteUser','reply_by_user');
    }

    public function replybyadmin()
    {
        return $this->belongsTo('App\Model\User','reply_by_admin');
    }
    public function replytouser()
    {
        return $this->belongsTo('App\Model\SiteUser','reply_to_user');
    }

    public function replytoadmin()
    {
        return $this->belongsTo('App\Model\User','reply_to_admin');
    }

    public function countmsg(){
        return $this->belongsTo('App\Model\Ticket','tickets_id');
    }


    // public function getCreatedAtAttribute($value)
    //     {
    //         echo $x = Config::get('app.timezone');

    //         die();
    //         $user = AuthController::authenticate();
             
    //         $timezone = $user ? $user->timezone : Config::get('app.timezone');

    //         return Carbon::createFromTimestamp(strtotime($value))
    //                         ->timezone($timezone)
    //                         ->toDateTimeString();
    //     }
}

?>