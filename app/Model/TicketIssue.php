<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TicketIssue extends Model
{
    public $timestamps = false;
    protected $guarded=[];

    protected $table = 'ticket_issues';

     
}
