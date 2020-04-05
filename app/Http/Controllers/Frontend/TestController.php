<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/
// Define Model
use App\Model\User; /* Model name*/


use App\Http\Requests;
use App\Http\Controllers\Controller;    
use Illuminate\Support\Facades\Request;
use Mail;
use Input; /* For input */
use Validator;
use Session;

use Illuminate\Pagination\Paginator;

use Hash;
use Auth;
use Cookie;
use App\Helper\helpers;
use Redirect;


//require_once "autoload.php";
//use App\Helper\TangoTest;



//require_once('tango/test.php');


class TestController extends BaseController {

    public function __construct() 
    {
        parent::__construct();
        $obj = new helpers();
        view()->share('obj',$obj);
    }
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */

   public function index(){
    
      // $platformName = 'checkoutsaver-test'; // RaaS v2 API Platform Name
      // $platformKey = 'CYkyKXZh?SEoODWM$Vnjo!HOmtm@$w!DN@aawNClS$Bzb'; // RaaS v2 API Platform Key


      // $result = testme();
      
      // echo "<pre>";print_r($result);
      //$obj = new TangoTest();
      /*$obj->testme();*/

     /* $obj1 = new TangoTest();

      $obj1->testme();*/


      //$client = new RaasLib\RaasClient($platformName, $platformKey);
   }
   
}