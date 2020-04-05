<?php namespace App\Http\Controllers\Frontend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

use App\Model\SiteUser;
use App\Model\Category;
use App\Model\Sitesetting;


use Input; /* For input */
use Validator;
use Session;
use Illuminate\Pagination\Paginator;
use DB;
use App;
use Redirect;
use App\Helper\helpers;
use Customhelpers;
use App\Model\Emailnotification; /* Model name*/
use App\Model\Emailnotification_Siteuser; /* Model name*/
use App\Model\Previousemail; /* Model name*/
use App\Model\Vendorcategories;/* Model name*/

class BaseController extends Controller {



	public function __construct() {

    $vendorcatscount=Vendorcategories::where('status',1)->count();
    if($vendorcatscount>0){
      $vendorcats=Vendorcategories::where('status',1)->get();
    }
    else{
      $vendorcats=array();
    }
    

      /*$this->setcustomsession();

      {

      $sessionvalue = Session::get('menwomen');
      if($sessionvalue==''){
        $sessionvalue = 1;
        Session::put('menwomen',1);
      }
      
      if(Session::has('user_id')){
        $Userdetails = SiteUser::where('id',Session::get('user_id'))->select('name','last_name','cashback')->first()->toArray();
        $first_name = $Userdetails['name'];
        $last_name = $Userdetails['last_name'];
        $cashback_amount = $Userdetails['cashback']?$Userdetails['cashback']:'0.00';

        $f_char = $l_char = '';
        if($first_name!="")
          $f_char = strtoupper($first_name[0]);
        if($last_name!="")
          $l_char = strtoupper($last_name[0]);

        $two_char = $f_char.$l_char;
        
        //$two_char = strtoupper(empty($first_name)?'':$first_name[0].empty($last_name)?'':$last_name[0]);
      }else{
        $two_char = '';
        $cashback_amount = 0;
      }

       $social_url_info  = Sitesetting::select('name','value')->whereIn('name',array('facebook_url','twitter_url','google_url','pinterest_url','instagram_url','bee_url'))->get()->toArray();
       //print_r($social_url_info);exit;
       
		  $category_details = Category::where('slug', '=', 'clothing')->first()->toArray();
      $subcategory_details = Category::where('parent_id', '=', $category_details['id'])->where('status',1)->whereIn('gender_cat',array($sessionvalue,3))->orderBy('name', 'asc')->get(['id','name','slug'])->toArray();

      


      // For shoes category
      $shoes_category_details = Category::where('slug', '=', 'shoes')->first()->toArray();
      $shoes_subcategory_details = Category::where('parent_id', '=', $shoes_category_details['id'])->where('status',1)->whereIn('gender_cat',array($sessionvalue,3))->orderBy('name', 'asc')->get(['id','name','slug'])->toArray();


      // For FINE WATCH category
      $watch_category_details = Category::where('slug', '=', 'fine-watches')->first()->toArray();
      $watch_subcategory_details = Category::where('parent_id', '=', $watch_category_details['id'])->where('status',1)->whereIn('gender_cat',array($sessionvalue,3))->orderBy('name', 'asc')->get(['id','name','slug'])->toArray();

      // For sports category
      $sports_category_details = Category::where('slug', '=', 'sports')->first()->toArray();
      $sports_subcategory_details = Category::where('parent_id', '=', $sports_category_details['id'])->where('status',1)->whereIn('gender_cat',array($sessionvalue,3))->orderBy('name', 'asc')->get(['id','name','slug'])->toArray();

      // For GROOMING category
      $grooming_category_details = Category::where('slug', '=', 'grooming')->first()->toArray();
      $grooming_subcategory_details = Category::where('parent_id', '=', $grooming_category_details['id'])->where('status',1)->whereIn('gender_cat',array($sessionvalue,3))->orderBy('name', 'asc')->get(['id','name','slug'])->toArray();

      // For ACCESSORIES category
      $accessories_category_details = Category::where('slug', '=', 'accessories')->first()->toArray();
      $accessories_subcategory_details = Category::where('parent_id', '=', $accessories_category_details['id'])->where('status',1)->whereIn('gender_cat',array($sessionvalue,3))->orderBy('name', 'asc')->get(['id','name','slug'])->toArray();

      $sale_category_details = Category::where('slug', '=', 'sale')->first()->toArray();

      $designer_brand_list = DB::table('brands')->where('status',1)->orderBy('brand_name', 'ASC')->get();
      

      $arr = array('subcategory_details' => $subcategory_details, 'category_details' => $category_details,'shoes_category_details' => $shoes_category_details, 'shoes_subcategory_details' => $shoes_subcategory_details,'watch_category_details' => $watch_category_details, 'watch_subcategory_details' => $watch_subcategory_details,'sports_category_details' => $sports_category_details, 'sports_subcategory_details' => $sports_subcategory_details,'grooming_category_details' => $grooming_category_details, 'grooming_subcategory_details' => $grooming_subcategory_details,'accessories_category_details' => $accessories_category_details, 'accessories_subcategory_details' => $accessories_subcategory_details,'sale_category_details' =>$sale_category_details,'designer_brand_list'=>$designer_brand_list);
      
      view()->share('data', $arr);
      view()->share('social_url_info', $social_url_info);

      view()->share('first_two_word', $two_char);
      view()->share('cashback_amount',$cashback_amount);

      $og_image = '';
      view()->share('og_image', $og_image);
      $og_url = '';
      view()->share('og_url', $og_url);
      $og_description = '';
      view()->share('og_description', $og_description);
      $title = '';
      view()->share('title', $title);
      $site_settings['fb_appid'] = '';
      view()->share('site_settings', $site_settings);*/

      view()->share('vendorcats', $vendorcats);
      view()->share('vendorcatscount', $vendorcatscount);

      $fbappiddata=Sitesetting::where("name","fbappid")->where("not_visible",'0')->get();
      $fbappidcount=Sitesetting::where("name","fbappid")->where("not_visible",'0')->count();
      if($fbappidcount>0){
      $fbappid=$fbappiddata[0]->value;
      }
      else{
      $fbappid="";
      }
      view()->share('fbappid', $fbappid);
  }

    public function index(){
    	
    }

    public function setcustomsession(){
      
      $data= Request::all();
      if(isset($data['val'])){
        Session::put('menwomen',$data['val']);
      }else{
        //Session::put('menwomen',1);
      }
      
    }
	
	public function authenticateUser($buy_url=""){

		/******************* Authenticating User *****************/
		if (Session::has('user_id'))
   		{
        
  			$user_details = SiteUser::find(Session::get('user_id'));
        if($user_details->status==0){
          Session::flush();
          //return redirect(App::getLocale().'/logout');
          header("Location:".url()."/user/logout");
          exit;
        }
        else{
          return $user_details;
        }
        
			  
   		}
   		else
   		{
   			//Session::flash('failure_message', 'Please login to continue.');
	    	//return redirect(App::getLocale().'/authenticate-login');
        
        //return redirect('/signin-user');
			  header("Location:".url()."/signin-user");
        exit;

   		}
		/******************* Authenticating User *****************/
	}
	
 	public function get_unique_alphanumeric_no( $length ) {
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $password = substr( str_shuffle( $chars ), 0, $length );
      return $password;
  }

  public function fetchStripSecretAPI(){
    $stripe_info  = Sitesetting::select('value')->where('name','STRIPE_SECRET_API')->first()->toArray();
    return $stripe_info['value'];
  }

  public function fetchStripPublicAPI(){
    $stripe_info  = Sitesetting::select('value')->where('name','STRIPE_PUBLIC_API')->first()->toArray();
    return $stripe_info['value'];
  }

  function get_site_email()
  {
    $site_settings_check = Sitesetting::where("name","=","email")->count();
    if($site_settings_check>0)
    {
      $site_settings_arr = Sitesetting::where("name","=","email")->first()->toArray();
      $stripe_api_key = $site_settings_arr['value'];
    }
    else
    {
      $stripe_api_key = "";
    }
    return $stripe_api_key;
  }
  function get_site_contact()
  {
    $site_settings_check = Sitesetting::where("name","=","contact")->count();
    if($site_settings_check>0)
    {
      $site_settings_arr = Sitesetting::where("name","=","contact")->first()->toArray();
      $stripe_api_key = $site_settings_arr['value'];
    }
    else
    {
      $stripe_api_key = "";
    }
    return $stripe_api_key;
  }
  function get_site_address()
  {
    $site_settings_check = Sitesetting::where("name","=","address")->count();
    if($site_settings_check>0)
    {
      $site_settings_arr = Sitesetting::where("name","=","address")->first()->toArray();
      $stripe_api_key = $site_settings_arr['value'];
    }
    else
    {
      $stripe_api_key = "";
    }
    return $stripe_api_key;
  }

  /***********************Email notifications on off in my profile**********************************/

  public function emailnotifyinprofile($userid,$datetime){


      $emailnotification=Emailnotification::where("status",'1')->get();
      $emailnotification_count=Emailnotification::where("status",'1')->count();

      if($emailnotification_count>0){

        foreach ($emailnotification as $key => $value) {
         
          Emailnotification_Siteuser::create(["siteusers_id"=>$userid,'emailnotifications_id'=>$value->id,'status'=>1,'created_at'=>$datetime,'updated_at'=>$datetime]);

        }

      }



  }

     /********************************************************************************
   *   CHECK USER EXISTS OR NOT AND CHECKING  PREVIOUS EMAIL MUST NOT BE USED                *
   *******************************************************************************/

  function postSiteuserCheck(){
  
    $data = Request::all();
    $datetime= Customhelpers::Returndatetime();

    $where_raw = "1=1";

    $where_raw .= " AND `email` = '".$data['email']."'";

    if($data['id']!=""){


      //$where_raw .= " AND `id`!= '".$data['hid_user_id']."'";

       $SiteUser=SiteUser::find($data['id']);
       $dbemailid=$SiteUser->email;

       if($dbemailid==$data['email']){

            echo "true"; // When site user edit the profile and gives same email id then update with same email id
            exit();
       }
       else{

        $Previousemail_details   =Previousemail::whereRaw($where_raw)->first();
            if(count($Previousemail_details)>0){

                echo "false";
                exit();
            }
            else{

            
                echo "true";
                exit();
            }

       }


    }

   // $user_details   = User::whereRaw($where_raw)->first();

    //check previous email exist or not

    $Previousemail_details   =Previousemail::whereRaw($where_raw)->first();

    if(count($Previousemail_details)>0){
      echo "false";
      exit();
    }
    else{
      
      echo "true";
      exit();
    }
    
  } 

  

}
