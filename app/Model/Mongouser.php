<?php
namespace App\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Mongouser extends Eloquent {

    //protected $collection = 'mytable';
    protected $collection = 'mytable';
    protected $guarded=[];
    protected $connection = 'mongodb';
}

?>