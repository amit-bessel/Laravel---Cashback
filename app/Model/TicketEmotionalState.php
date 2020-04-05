<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TicketEmotionalState extends Model
{
    public $timestamps = false;
    protected $guarded=[];

    protected $table = 'ticket_emotional_state';
     
}

?>