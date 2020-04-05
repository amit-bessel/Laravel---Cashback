<?php namespace App\Http\Controllers\Service; /* path of this controller*/

use App\Http\Requests;
use App\Model\UserLoginDetail; /* Model name*/
use App\Model\Restaurant; /* Model name*/
use App\Model\Sitesetting; /* Model name*/
use App\Model\SiteUser; /* Model name*/

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use Illuminate\Pagination\Paginator;
use DB;

use App\Helper\helpers;



class BaseController extends Controller {

	public function __construct() 
    {		

    }
	
	/*	Fetch user_id from  "UserLoginDetail" table	*/
	public function getUserId($token=""){
		$user_token_info 		= UserLoginDetail::where('token',$token)->first();
		return $user_token_info['user_id'];
	}

	public function getUserDetailById($user_id=""){
		$user_det 		= SiteUser::where('id',$user_id)->first();
		return $user_det;
	}
	
	public function getUserDetail($token=""){
		$user_details = UserLoginDetail::with('getUserDetail')->where('token',$token)->first();
		return $user_details;
	}

	public function fetchRestaurantId($site_user_id=""){
		$restaurant_info  = Restaurant::where('restaurant_usr_id',$site_user_id)->first();
		return $restaurant_info['id'];
	}
	
	public function fetchRestaurantDetails($site_user_id=""){
		$restaurant_info  = Restaurant::where('restaurant_usr_id',$site_user_id)->first();
		return $restaurant_info;
	}

	public function fetchStripSecretAPI(){
		$stripe_info  = Sitesetting::select('value')->where('name','STRIPE_SECRET_API')->first()->toArray();
		return $stripe_info['value'];
	}

	public function fetchStripPublicAPI(){
		$stripe_info  = Sitesetting::select('value')->where('name','STRIPE_PUBLIC_API')->first()->toArray();
		return $stripe_info['value'];
	}

}
