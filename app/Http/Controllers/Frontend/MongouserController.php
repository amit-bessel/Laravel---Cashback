<?php namespace App\Http\Controllers\Frontend;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Model\Mongouser;
use App\Model\SiteUser;
use App\Model\Walletdetails; /* Model name*/
use App\Model\Tangoorder; /* Model name*/
use App\Model\User; /* Model name*/

use Customhelpers;


use App\Book;

use App\Http\Requests;
use App\Helper\helpers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use DB;
use Mail;
use Hash;
use Auth;
use Cache;
use Cookie;
use Yajra\Datatables\Datatables;

use RaasLib;

require_once('vendor/Stripe/init.php');


//require_once('tangoraas/test/catalogSDK.php');

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe_CardError;
use Stripe\Stripe_InvalidRequestError;

require_once('tangoraas/Tangoapi.php');

class MongouserController extends BaseController {

  /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
    	parent::__construct();
    }
    
	/********************************************************************************
	 *								GET CATALOG LIST									*
	 *******************************************************************************/
    function getIndex()
    {
    	echo "hh";exit();


    }
    function getDataInsert()
    {
        $datez=date("Y-m-d");


        Mongouser::create(['name' => 'John22244','created_at'=>$datez,'updated_at'=>$datez]);
        //$user = DB::connection('mongodb')->collection('users')->get();
        //echo "<pre>";
        //print_r($user);exit();
       // Mongouser::create(['name'=>'sourav']);

    }

/********************************************************************************
	 *								CREATE ORDER								*
 *******************************************************************************/




    function postCreateorder(){


    	if (Session::has('user_id')){



			

    		Walletdetails::create(['siteusers_id'=>$siteusers_id,'purpose'=>$purpose,'amount'=>$amount,'total'=>$total,'status'=>$newstatus,'created_at'=>$datetime,'currencycode'=>$currencycode,'exchangerate'=>$exchangerate]);

    	}
    	else{
    		return redirect('login');
    	}

    	


    }

    
	
	
    
}
