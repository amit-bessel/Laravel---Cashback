<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class Ticket extends Model
{
    public $timestamps = false;
    protected $guarded=[];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $table = 'tickets';

    public function  username()
    {
        return $this->belongsTo('App\Model\SiteUser','siteusers_id');
    }

    public function  issuestype()
    {
        return $this->belongsTo('App\Model\TicketIssue','issuetype_id');
    }

    public function  emotionalState()
    {
        return $this->belongsTo('App\Model\TicketEmotionalState','emotionstate_id');
    }

    // public function getCreatedAtAttribute($value)
    // {
    //     return Carbon::createFromTimestamp(strtotime($value))
    //         ->timezone('America/New_York')
    //         ->toDateTimeString()
    //     ;
    // }

    // public function getCreatedAtAttribute($value)
    //     {

            
    //         $user = Auth::user();
             
    //         $timezone = $user ? $user->timezone : Config::get('app.timezone');

    //         return Carbon::createFromTimestamp(strtotime($value))
    //             ->timezone($timezone)
    //             ->toDateTimeString()
    //         ;
    //     }

     
}

?>