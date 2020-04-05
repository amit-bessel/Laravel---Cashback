<?php
namespace App\Helper;
use App\Model\SiteUser;/*Model Name*/
use Session;
class Customhelpers{
    
	/****************Return date time **********************/
    public static function Returndatetime()
    {
    	//date_default_timezone_set('Asia/Kolkata');
		$timestamp = date("Y-m-d H:i:s");
        return $timestamp;
    }

	/****************Get user details **********************/

    public static function  getUserDetails(){

    	if (Session::has('user_id')){

    		$id=Session::get('user_id');
    		$userprofilehead=SiteUser::find($id);
    		return $userprofilehead;
    	}
    	else{
    		return redirect('login');
    	}
    }
}