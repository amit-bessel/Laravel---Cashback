<?php namespace App\Http\Controllers\Frontend;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Model\SiteUser;
use App\Model\Walletdetails; /* Model name*/
use App\Model\Tangoorder; /* Model name*/
use App\Model\User; /* Model name*/
use App\Model\Sitesetting; /* Model name*/
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

use App\Model\Emailnotification; /* Model name*/
use App\Model\Emailnotification_Siteuser; /* Model name*/

use App\Model\Giftcard; /* Model name*/
use App\Model\Giftcarddetail; /* Model name*/
use App\Model\Giftcardimage; /* Model name*/

require_once('tangoraas/Tangoapi.php');

class BrandController extends BaseController {

  /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
    	parent::__construct();
    }


     /************************ Currency converter  start ***************************/

   public function currencyconverter($from_Currency,$to_Currency,$amount) {
            $from_Currency = urlencode($from_Currency);
            $to_Currency = urlencode($to_Currency);

            $get = file_get_contents("https://finance.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency");
            // $get = file_get_contents("https://finance.google.com/finance/converter?a=20&from=CAD&to=USD");
            /*echo "<pre>";
            print_r($get);
            exit();*/
            $get = explode("<span class=bld>",$get);
            $get = explode("</span>",$get[1]);
            $converted_currency = preg_replace("/[^0-9\.]/", null, $get[0]);
            return $converted_currency;
    }

    function convertCurrency($amount, $from, $to) {
     $url = 'http://finance.google.com/finance/converter?a=' . $amount . '&from=' . $from . '&to=' . $to;
     $data = file_get_contents($url);
         preg_match_all("/<span class=bld>(.*)<\/span>/", $data, $converted);
         $final = preg_replace("/[^0-9.]/", "", $converted[1][0]);
        return round($final, 3);
   }

    /************************ Currency converter  end ***************************/

    

    /********************************************************************************
	 *								GET BRAND DETAILS									*
	 *******************************************************************************/
    function getBrandDetails($id)
    {
    	if (Session::has('user_id')){

    	
    	
    	

    	/****************Get user details ******************************/

        $userprofileheadinfo = Customhelpers::getUserDetails();


    	$siteusers_id=Session::get('user_id');
    	$userdetails=SiteUser::find($siteusers_id);
    	//echo $userdetails->wallettotalamount;exit();
    	
    	// echo "<pre>";
    	// print_r($userdetails);exit();

    	$brandkey=$id;
    	//echo $brandkey;exit();
    	//$obj1=new Tangoapi();

    	//$result = $obj1->testme();


    	// $catalog_details = getcatalog();
        $tangoapikeyar=$this->gettangoapikeyfromdb();

        $tangoapiplatformkey=$tangoapikeyar["tangoapiplatformkey"];
        $tangoapiplatformname=$tangoapikeyar["tangoapiplatformname"];
        
        /*
        $catalog_details =getcatalog($tangoapiplatformkey,$tangoapiplatformname);
        
      
       
        foreach ($catalog_details->brands as $key => $value) {

            $new_item = array();
            if($value->brandKey== $id) {
                foreach ($value->items as $key2 => $value2) {
                    if($value2->currencyCode != 'USD')
                    {
                        $from_Curr = $value2->currencyCode;
                       
                        if($value2->maxValue != ''){
                            $tempmaxValue = $this->currencyconverter($from_Curr, "USD" , $value2->maxValue);
                            if($tempmaxValue < 100)
                                
                                array_push($new_item,$value2);  
                        }
                        if($value2->maxValue == '' && $value2->faceValue != ''){
                            $tempfaceValue = $this->currencyconverter($from_Curr, "USD" , $value2->faceValue);
                            if($tempfaceValue < 100)
                                
                                array_push($new_item,$value2);
                        }
                        
                    }
                    else{
                        if($value2->maxValue != '' && $value2->maxValue < 100){
                            array_push($new_item,$value2);
                        }
                        if($value2->maxValue == '' && $value2->faceValue < 100){
                            array_push($new_item,$value2);
                        }
                    }
                }
            }

            if(empty($new_item)){
                unset($catalog_details->brands[$key]);
            }
            else{
                $value->items = $new_item;   
            }

                    
        }
    	
        */

        $catalog_details=array();
		$catalog_class 			= "active";
        $module_head 			= "View Catalog Details";
		

        $title	= "View Catalog Details";
		
        $giftcarddetails=Giftcarddetail::with("giftcard","giftcard.giftcardimages")->where('giftcard_id',$id)->get();
        
		 //$catalog_details->setPath('catalog_details');
        return view('frontend.tangoapi.branddetails',compact('userprofileheadinfo','catalog_details','userdetails','giftcarddetails'),array('title'=>$title,'module_head'=>$module_head,'brandkey'=>$brandkey ));

    }

	else{
		return redirect('login');
	}	


    }



    /*****************Get key details from database **************************/

  public function gettangoapikeyfromdb(){


    
        
      $Sitesetting_key_data=Sitesetting::where("name","tangoapiplatformkey")->where("not_visible",0)->get();
      $Sitesetting_key_data_count=Sitesetting::where("name","tangoapiplatformkey")->where("not_visible",0)->count();
      if($Sitesetting_key_data_count>0){
         $tangoapiplatformkey=$Sitesetting_key_data[0]->value;
      }
      else{
        $tangoapiplatformkey="";
      }
      
      
      $Sitesetting_name_data=Sitesetting::where("name","tangoapiplatformname")->where("not_visible",0)->get();
      $Sitesetting_name_data_count=Sitesetting::where("name","tangoapiplatformname")->where("not_visible",0)->count();

      if($Sitesetting_name_data_count>0){

         $tangoapiplatformname=$Sitesetting_name_data[0]->value;
      }
      else{
        $tangoapiplatformname="";
      }


      $Sitesetting_name_customeridentifier=Sitesetting::where("name","tangoapicustomeridentifier")->where("not_visible",0)->get();
      $Sitesetting_name_customeridentifier_count=Sitesetting::where("name","tangoapicustomeridentifier")->where("not_visible",0)->count();

      if($Sitesetting_name_customeridentifier_count>0){

         $tangoapicustomeridentifier=$Sitesetting_name_customeridentifier[0]->value;
      }
      else{
        $tangoapicustomeridentifier="";
      }


      $Sitesetting_name_accountidentifier=Sitesetting::where("name","tangoapiaccountidentifier")->where("not_visible",0)->get();
      $Sitesetting_name_accountidentifier_count=Sitesetting::where("name","tangoapiaccountidentifier")->where("not_visible",0)->count();

      if($Sitesetting_name_accountidentifier_count>0){

         $tangoapiaccountidentifier=$Sitesetting_name_accountidentifier[0]->value;
      }
      else{
        $tangoapiaccountidentifier="";
      }

      return array("tangoapiplatformkey"=>$tangoapiplatformkey,"tangoapiplatformname"=>$tangoapiplatformname,"tangoapicustomeridentifier"=>$tangoapicustomeridentifier,"tangoapiaccountidentifier"=>$tangoapiaccountidentifier);
	}





	/********************************************************************************
	 *								GET CATALOG LIST									*
	 *******************************************************************************/
    function getCatalog()
    {
    	if (Session::has('user_id')){

        $datetime= Customhelpers::Returndatetime();    
    	/****************Get user details ******************************/

        $userprofileheadinfo=Customhelpers::getUserDetails();
        

    	//$obj1=new Tangoapi();

    	//$result = $obj1->testme();



        $tangoapikeyar=$this->gettangoapikeyfromdb();
    	
    	$tangoapiplatformkey=$tangoapikeyar["tangoapiplatformkey"];
    	$tangoapiplatformname=$tangoapikeyar["tangoapiplatformname"];

    	$catalog_details =getcatalog($tangoapiplatformkey,$tangoapiplatformname);

    	

    	// print_r($catalog_details->brands);
    	// exit;
    	// $catalog_details2 = (array) $catalog_details;

        //==================Start Convert currency to usd of tango api and store in db it will be used in cron=====================

        /*
    	foreach ($catalog_details->brands as $key => $value) {
    		
    		$new_item = array();
            $flag=0;
    		foreach ($value->items as $key2 => $value2) {
    			
    			if($value2->currencyCode != 'USD')
    			{*/




    				/*$from_Curr = $value2->currencyCode;
    				
    				if($value2->maxValue != ''){
    					$tempmaxValue = $this->currencyconverter($from_Curr, "USD" , $value2->maxValue);
    					if($tempmaxValue < 100)
                        {
                           

                            $value2->converted_maxValue=$tempmaxValue;
                            $value2->converted_minValue=$this->currencyconverter($from_Curr, "USD" , $value2->minValue);
                            $value2->converted_currencyCode="USD";
    						array_push($new_item,$value2);
                        }	
    				}
    				if($value2->maxValue == '' && $value2->faceValue != ''){
    					$tempfaceValue = $this->currencyconverter($from_Curr, "USD" , $value2->faceValue);
    					if($tempfaceValue < 100){

                            

                            $value2->converted_currencyCode='USD';
                            $value2->converted_faceValue=$tempfaceValue;
    						array_push($new_item,$value2);
                        }
    				}*/

                    





                    /*
    				
    			}
    			else{

                    */






                     /*$value2->converted_currencyCode='USD';
                     $value2->converted_faceValue= 0;
                     $value2->converted_maxValue=0;
                     $value2->converted_minValue = 0;*/

	    			/*if($value2->maxValue != '' && $value2->maxValue < 100){
                        $value2->converted_maxValue=$value2->maxValue;
                        $value2->converted_minValue = $value2->minValue;
	    				array_push($new_item,$value2);
	    			}
	    			if($value2->maxValue == '' && $value2->faceValue < 100){
                         $value2->converted_faceValue=  $value2->faceValue;
	    				array_push($new_item,$value2);
	    			}*/
                    






                    /*

                    if($value2->faceValue != '' && $value2->faceValue < 100){
                         // $value2->converted_faceValue=  $value2->faceValue;
                        array_push($new_item,$value2);
                    }

    			}
    		}

    		if(empty($new_item)){
    			unset($catalog_details->brands[$key]);
    		}
    		else{
    			$value->items = $new_item;   
    		}

    		
    		 		
    	}
        // echo "<pre>";
        // print_r($catalog_details->brands);
        // exit();

        

        Giftcard::where('id','>','0')->delete();

      	foreach ($catalog_details->brands as $key => $value)
         {
         


               $giftcard=Giftcard::create(['brandkey'=>$value->brandKey,'brandname'=>$value->brandName,'disclaimer'=>$value->disclaimer,'description'=>$value->description,'shortdescription'=>$value->shortDescription,'terms'=>$value->terms,'created_at'=>$datetime,'updated_at'=>$datetime,'displaystatus'=>1]);
               $lastinsertid=$giftcard->id;
               foreach ($value->imageUrls as $key => $imgvalue) {
                   Giftcardimage::create(['giftcard_id'=>$lastinsertid,'imageurl'=>$imgvalue,'created_at'=>$datetime,'updated_at'=>$datetime]);
               }
               
                foreach ($value->items as $key2 => $value2) {

                    if($value2->minValue==""){
                        $value2->minValue=0.0;
                    }
                    if($value2->maxValue==""){
                        $value2->maxValue=0.0;
                    }
                    if($value2->faceValue==""){
                        $value2->faceValue=0.0;
                    }

                    Giftcarddetail::create(['giftcard_id'=>$lastinsertid,'utid'=>$value2->utid,'rewardname'=>$value2->rewardName,'currencycode'=>$value2->currencyCode,'status'=>$value2->status,'valuetype'=>$value2->valueType,'rewardtype'=>$value2->rewardType,'created_at'=>$datetime,'updated_at'=>$datetime,'countries'=>$value2->countries[0],'min_value'=>$value2->minValue,'max_value'=>$value2->maxValue,'facevalue'=>$value2->faceValue]);

                }

        }
    	
       



       */



        //==================End Convert currency to usd of tango api and store in db it will be used in cron=====================

		$catalog_class 			= "active";
        $module_head 			= "View Catalog Details";
		

        $title	= "View Catalog Details";
		
        if (Session::has('user_id')){

        	$siteusers_id=Session::get('user_id');
        	$siteuser=SiteUser::find($siteusers_id);

            $usergiftcard=Giftcard::with('giftcardimages')->where('displaystatus',1)->get();

        	 return view('frontend.tangoapi.catalog',compact('catalog_details','userprofileheadinfo','usergiftcard'),array('title'=>$title,'module_head'=>$module_head,'siteuser'=>$siteuser ));
        }
		 //$catalog_details->setPath('catalog_details');
        return view('frontend.tangoapi.catalog',compact('catalog_details','userprofileheadinfo'),array('title'=>$title,'module_head'=>$module_head ));
    }
    else{
		return redirect('login');
	}

    }







/********************************************************************************
	 *								CREATE ORDER								*
 *******************************************************************************/




    function postCreateorder(){


    	if (Session::has('user_id')){

    		

			$data= Request::all();
			//echo "<pre>";
			//print_r($data);exit();

			$userid=Session::get('user_id');
			$userdetails=SiteUser::find($userid);
			$dbuseremail=$userdetails->email;


			$brandkey=$data['brandkey'];
			$utid=$data["utid"];
			$useramount=$data["amount"];
			$facevalue=$data["faceValue"];
			$minvalue=$data["minValue"];
			$maxvalue=$data["maxValue"];

			$brandname=$data['brandname'];
			
			$giftcardimage=$data['giftcardimage'];

			if($useramount==''){

    			Session::flash('failure_message', 'Please give the right amount.'); 
        		Session::flash('alert-class', 'alert alert-danger'); 
        		return redirect('brand/details/'.$brandkey);
    		}

    		$regex="/(\d+(\.\d+)?)/";

			if (!preg_match($regex, $useramount)) {
				Session::flash('failure_message', 'Please give the right amount.Amount must be integer or double.'); 
        		Session::flash('alert-class', 'alert alert-danger'); 
        		return redirect('brand/details/'.$brandkey);
			} 

			if($facevalue==''){


				if($useramount>=$minvalue&&$useramount<=$maxvalue ){

				}
				else
				{
					Session::flash('failure_message', 'Please give the amount between min value and max value.'); 
        			Session::flash('alert-class', 'alert alert-danger'); 
        			return redirect('brand/details/'.$brandkey);
				}
			}

			if(!empty($data['sendmailcheckbox'])){

				$email=$data['email'];
				$firstname=$data['firstname'];
				$lastname=$data['lastname'];
				$sendmailcheckbox=$data['sendmailcheckbox'];
				$status=true;
				$receiverstatus=1; // mail to friend


				if($email=='' || $firstname=='' || $lastname==''){

					Session::flash('failure_message', 'Please provide correct receiver email,name if you want to send mail.'); 
        			Session::flash('alert-class', 'alert alert-danger'); 
        			return redirect('brand/details/'.$brandkey);
				}
				if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
				//echo("$email is a valid email address");
				}
				else{
					Session::flash('failure_message', 'Email is not a valid email address.'); 
        			Session::flash('alert-class', 'alert alert-danger'); 
        			return redirect('brand/details/'.$brandkey);
				}
			}
			else{
				
                    //echo "<pre>";
                    //print_r($userdetails);exit();
				
					$email=$userdetails->email;
					$firstname=$userdetails->firstname;
					$lastname=$userdetails->lastname;
				    $status=true;
				    $receiverstatus=0;// mail to same user

			}

			$siteuserid=Session::get('user_id');

			//Check purchase gift card confirmation status in profile is on or off

			$emailnotification=Emailnotification::where('slug','Purchase-confirmation-of-gift-card')->where('status',1)->get();
			
			$emailnotification_count=Emailnotification::where('slug','Purchase-confirmation-of-gift-card')->where('status',1)->count();
			if($emailnotification_count>0){
			    $emailnotification_id=$emailnotification[0]->id;
				

				$emailnotification_Siteuser=Emailnotification_Siteuser::where('siteusers_id',$siteuserid)->where("emailnotifications_id",$emailnotification_id)->get();


				$emailnotification_Siteuser_count=Emailnotification_Siteuser::where('siteusers_id',$siteuserid)->where("emailnotifications_id",$emailnotification_id)->count();

				if($emailnotification_Siteuser_count>0){

					$mailstatus=$emailnotification_Siteuser[0]->status;
					if($mailstatus==0){

						$status=0;
						
					}
					
				}
			}

            if(!empty($data['sendmailcheckbox'])){
                $status=1;
            }
			
			//create user order
			$tangoapikeyar=$this->gettangoapikeyfromdb();
    	
    		$tangoapiplatformkey=$tangoapikeyar["tangoapiplatformkey"];
    		$tangoapiplatformname=$tangoapikeyar["tangoapiplatformname"];

            $tangoapiaccountidentifier=$tangoapikeyar["tangoapiaccountidentifier"];
            $tangoapicustomeridentifier=$tangoapikeyar["tangoapicustomeridentifier"];
  
            $total=$useramount;

            $siteusers_id=Session::get('user_id');

            $userdetails=SiteUser::find($siteusers_id);
            
            if(!empty($userdetails)){


                $wallettotalamount=$userdetails->wallettotalamount;
                
                $remainingwalletamount=$wallettotalamount-$total;
                
                
            }
            
            //======Checking the wallet balance=======

            if($remainingwalletamount< 0){

                Session::flash('failure_message', 'You have not sufficient fund.'); 
                Session::flash('alert-class', 'alert alert-danger'); 
                return redirect('brand/details/'.$brandkey);
            }



            //call the same order function with proper mail status and insert into db
			$order_details =getcreateorder($utid,$useramount,$email,$firstname,$lastname,$status,$dbuseremail,$tangoapiplatformkey,$tangoapiplatformname,$tangoapiaccountidentifier,$tangoapicustomeridentifier);
			
			
    		$purpose='Gift card purchase';
           
    		$referenceOrderID=$order_details->referenceOrderID; // main order id return from tango api
    		$amount=$order_details->amountCharged->value;
    		$total=$order_details->amountCharged->total;
    		$exchangerate=$order_details->amountCharged->exchangeRate;
    		$currencycode=$order_details->amountCharged->currencyCode;
    		$rewardname=$order_details->rewardName;
    		$status=$order_details->status;
    		$utid=$order_details->utid;
    		$externalRefID=$order_details->externalRefID; // unique order id generated from our site
    		$datetime= Customhelpers::Returndatetime();
    		if($status=='COMPLETE'){
    			$newstatus=1;
    		}
    		if($exchangerate==''){
    			$exchangerate=0;
    		}

    		 $itemdetails=$rewardname." ,<br/> Order Id : ".$order_details->referenceOrderID;

    		

    		Walletdetails::create(['siteusers_id'=>$siteusers_id,'purpose'=>$purpose,'amount'=>$amount,'total'=>$total,'status'=>$newstatus,'created_at'=>$datetime,'currencycode'=>$currencycode,'exchangerate'=>$exchangerate,'itemdetails'=>$itemdetails,'walletstatus'=>'1']); // insert into transaction table

    		Tangoorder::create(['siteusers_id'=>$siteusers_id,'currencycode'=>$currencycode,'exchangerate'=>$exchangerate,'total'=>$total,'value'=>$amount,'rewardname'=>$rewardname,'status'=>$status,'utid'=>$utid,'externalrefid'=>$externalRefID,'created_at'=>$datetime,'referenceorderid'=>$referenceOrderID,'receiverfirstname'=>$firstname,'receiverlastname'=>$lastname,'receiveremail'=>$email,'receiverstatus'=>$receiverstatus,'brandname'=>$brandname,'giftcardimage'=>$giftcardimage]); // insert into order table

    		
    		//====Update the user wallet amount=====
    		$userdetails->wallettotalamount=$remainingwalletamount;
    		$userdetails->save();

			$title='Order Gift Card';
			$module_head='Order Gift Card';

            /****************Get user details ******************************/

            $userprofileheadinfo=Customhelpers::getUserDetails();

    		return view('frontend.tangoapi.tangoordersuccess',compact('externalRefID','referenceOrderID','userprofileheadinfo','userdetails'),array('title'=>$title,'module_head'=>$module_head ));
    	}
    	else{
    		return redirect('login');
    	}

    	


    }

    /********************************************************************************
	 *								 USER VIEW DETAILS									*
	 *******************************************************************************/
	
	function getView($id='')
	{



        $user_class 			= "active";
        $module_head 			= "View User Details";
		$user_id				= $id;
        $user_details 			= User::where('id', '=', $id)->first();
        if(!$user_details){
        	Session::flash('failure_message', 'User not found.'); 
        	Session::flash('alert-class', 'alert alert-danger'); 
        	return redirect('admin/patient/list');
        	exit;
        }
        $title	= "View User Details";
		return view('admin.user.view_user_details',compact(
			'module_head',
			'user_id',
			'user_class',
			'user_details',
			'title'
			));



    }

	/********************************************************************************
	 *									EDIT USER 									*
	 *******************************************************************************/

    function getEdit($id='')
	{
        $user_class 			= "active";
        $module_head 			= "Edit User Details";
		$user_id				= $id;
        $user_details 			= User::where('id', '=', $id)->first();
        if(!$user_details){
        	Session::flash('failure_message', 'User not found.'); 
        	Session::flash('alert-class', 'alert alert-danger'); 
        	return redirect('admin/patient/list');
        	exit;
        }
        $title	= "Edit User Details";
		return view('admin.user.edit_user_details',compact(
			'module_head',
			'user_id',
			'user_class',
			'user_details',
			'title'
			));
    }


    function postEdit($id='')
	{
		/* call custom helper */
		$datetime= Customhelpers::Returndatetime();
		$data				= Request::all();
		
		$user_id			= $id;
		$user_pass			= "";
		$user_details		= User::where('id', $user_id)->first();
		$user_pass			= $user_details->password;
		$update_user		= User::where('id', $user_id)
									->update([
										'firstname'          => trim($data['firstname']),
										'lastname'     => trim($data['lastname']),
									
										'title'    		=> $data['title'],
										'email'       	=> $data['email'],
										'contact'       => preg_replace('/[^0-9]+/', '',$data['contact']),
										'updated_at'=>$datetime,
									]);
		if($data['password']!=''){
			$user_pass				= $data['password'];
			$update_password		= User::where('id', $user_id)
										->update([
											'password'      => Hash::make($data['password'])
										]);
										
		}
		Session::flash('success_message', 'User details has been updated successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/user/list');
    }



	/********************************************************************************
	 *									ADD USER 									*
	 *******************************************************************************/

	function getAdd()
	{
		$user_class = "active";
	    $module_head = "Add User";
		return view('admin.user.add_user_details',compact('module_head','user_class'));
	}


  function postAdd($id='')
	{
		/* call custom helper */
		$datetime= Customhelpers::Returndatetime();
		
		$data = Request::all();
		//echo '<pre>';print_r($data);exit;
	    $user= User::create([
                 'firstname'    => $data['name'],
                 'role'    => $data['usertype'],
                 'title'    => $data['title'],
                 'lastname'    => $data['last_name'],
                 'email'  => $data['email'],
                 'contact'  => preg_replace('/[^0-9]+/', '',$data['contact']),
                 'password'  => Hash::make($data['password']),
                 'status'=>1,
                 'created_at'=>$datetime,

                 ]);
               $lastInsertedId = $user->id;

              

            ###################################Sening mail starts##############################
			
			$user_name 	= $data['name'];
			$user_email = $data['email'];
			$user_pass  = $data['password'];
			if($data['usertype'] == 1){
				$usertype = "admin";
			}else if($data['usertype'] == 2){
				$usertype = "sub-admin";
			}
			
			$admin_users_email = "";
			
			$sitesettings = DB::table('sitesettings')->where('name','email')->first();
			if(!empty($sitesettings))
			{
				$admin_users_email = $sitesettings->value;
			}
			
			$subject = "You have added as ".$usertype." in Cashback justin";
			
			$message_body = "Admin has added you in Cashback justin.Your password is: ".$user_pass;
			
			################# SEND MAIL TO SITE-USER -start ################################
			
			
			
			if(($_SERVER['REMOTE_ADDR'] != 'localhost') && ($_SERVER['REMOTE_ADDR'] != '192.168.1.115')) //check if code is ruuning in localhost
			{
				$mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
				{
					$message->from($admin_users_email,'Cashback justin');
		
					$message->to($user_email)->subject($subject);
				});
			}
			################# SEND MAIL TO SITE-USER -end ################################


		


          
		Session::flash('success_message', 'User details has been Saved successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/user/list');
    }

	
	
	/********************************************************************************
	 *						CHECK USER EXISTS OR NOT								*
	 *******************************************************************************/
	function postCheck(){
		$data	= Request::all();
		
		$where_raw = "1=1";
		$where_raw .= " AND `email` = '".$data['email']."'";
		if($data['hid_user_id']!=""){
			$where_raw .= " AND `id`!= '".$data['hid_user_id']."'";
		}
		$user_details 	= User::whereRaw($where_raw)->first();
		if(count($user_details)>0){
			echo "false";
		}
		else{
			echo "true";
		}
		
	}
	
	/********************************************************************************
	 *								REMOVE USER										*
	 *******************************************************************************/
	function getRemove($id=""){
		$user_details = User::find($id);
		if(!empty($user_details['image'])){
			@unlink('uploads/profile_image/'.$user_details['image']);
		}
        $user_details->delete();
       
    	Session::flash('success_message', 'User has been removed successfully.'); 
    	Session::flash('alert-class', 'alert alert-success'); 
    	return redirect('admin/user/list');
        
	
	}
	
	/********************************************************************************
	 *							CHANGE STATUS										*
	 *******************************************************************************/
	function getStatus(){
		
		$post_data  = Request::all();
        $status 	= $post_data['this_val'];
        $id 		= $post_data['this_id'];
        $user 	= User::find($id);
        $user->status = $status;
        $user->save();
        echo "1";
		exit();
	
	}

	/********************************************************************************
	 *							AJAX DATATABLE LISTING USERS								*
	 *******************************************************************************/

	function ajaxPatientsList(){
		$search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
        $where = "";

        $role=Auth::user()->role;
        $userId = Auth::id();

        if($role==2){

        	$where.=" `id`= '".$userId."' AND ";
        }

		 if($search_key != ''){
	            $where 			.= "(`firstname` LIKE '%".$search_key."%' OR CONCAT(firstname,' ',lastname) LIKE '%".$search_key."%' OR `lastname` LIKE '%".$search_key."%' OR `email` LIKE '%".$search_key."%' OR `contact` LIKE '%".$search_key."%') AND ";
	     }
        if($active != ''){
            $where 		   .= "`status`= '".$active."' AND ";
        }
        $where 		   .= '1';
        $all_patients =  User::select(['id', 'firstname','lastname','contact', 'email', 'created_at', 'updated_at','status','role'])->whereRaw($where)->orderBy('id', 'DESC');

		return Datatables::of($all_patients)
		->addColumn('checkbox_td', function ($all_patients) {
                return '<input type="checkbox"  recordType="multipleRecord" multipleRecord="'.$all_patients->id.'" />';
            })
		 ->addColumn('action', function ($all_patients) {
                return '<a href="'.url().'/admin/user/edit/'.$all_patients->id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;<a href="'.url().'/admin/user/view/'.$all_patients->id.'"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;<a href="javascript:void(0);" onclick="userJs.remove('.$all_patients->id.')"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
            })
		 ->editColumn('name', function ($all_patients) {
		 		$fullname = $all_patients->firstname." ".$all_patients->lastname;
            	return $fullname;

            })
		 ->editColumn('status', function ($all_patients) {
		 	if($all_patients->status == 1){
             	$status_html = '<select style="width:94%;" name="status" id="user_active_'.$all_patients->id.'" onchange="userJs.changeStatus(this.value,'.$all_patients->id.')"><option value="1" selected>Active</option><option value="0">Inactive</option></select><br /><span class="alert-success" id="success_status_span_'.$all_patients->id.'" style="display:none;"></span>';
		 	}else{
		 		$status_html = '<select style="width:94%;" name="status" id="user_active_'.$all_patients->id.'" onchange="userJs.changeStatus(this.value,'.$all_patients->id.')"><option value="1">Active</option><option value="0" selected>Inactive</option></select><span class="alert-success" id="success_status_span_'.$all_patients->id.'" style="display:none;"></span>';

		 	}
		 	return $status_html;
            })
		->make(true);
	}

	
	


	 function  getDeleteAll()
    {
        $post_id  = Request::all();
        $id = explode(",", $post_id['ids']);
        $city = SiteUser::whereIn('id',$id)->delete();
        echo "1";
        exit;
    }

    function getSearchUser(){

    	$term = $_GET['term'];
    	$order_details_arr = array();
        $order_arr = SiteUser::orWhere(function ($query) use($term) {
                				 $query->orWhere('name', 'like', $term . '%')
                      				   ->orWhere('email', 'like', $term . '%')
                      				   ->orWhere('contact', 'like', $term . '%');
                      			})->get();
        if(!empty($order_arr))
		{
			$order_arr = $order_arr->toArray();
			$i = 0;
			foreach($order_arr as $order_list)
			{
				$order_details_arr[$i]['key'] = $order_list['id'];
				$order_details_arr[$i]['value'] = $order_list['name'];
				$i++;
			}
		}
		echo json_encode($order_details_arr);
		exit;

    }
    
}
