<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/
// Define Model
use App\Model\Brandmember; /* Model name*/
use App\Model\Newsletter;  /* Model name*/
use App\Model\Blog;  /* Model name*/
use App\Model\Country;/*Model Name*/
use App\Model\Zone;/*Model Name*/
use App\Model\City;/*Model Name*/
use App\Model\Adverts;/*Model Name*/
use App\Model\AdvertImage; /* Model name*/
use App\Model\Sitesetting;
use App\Model\SiteUser;
use App\Http\Requests;
use App\Http\Controllers\Controller;    
use Illuminate\Support\Facades\Request;
use Mail;
use Input; /* For input */
use Validator;
use Session;
use Imagine\Image\Box;
use Image\Image\ImageInterface;
use Illuminate\Pagination\Paginator;
use App\Model\UtilityCuretedFeature;
use DB;
use Hash;
use Auth;
use Cookie;
use App\Helper\helpers;
use Cart;
use App\Model\Subscription;
use Redirect;
use Lang;
use App;
use Config;
//use Socialize;
use App\Model\Address; 

class AccountController extends BaseController {

    public function __construct() 
    {
        parent::__construct();
	       
        //$this->list_last_minute_deal = 10;

        $obj = new helpers();
        view()->share('obj',$obj);

        $active_menu = "";
       	view()->share('active_menu',$active_menu);

		if (Session::has('user_id'))
   		{
			//return redirect(App::getLocale().'/dashboard');
			header("Location:".url()."/".App::getLocale());
			exit;
   		}

    }
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */

    
   	################Home Listing of hotels starts(Home Page of the site)####################
    public function index()
    {
    	
   		
    }
		
    ######Home Listing of hotels ends(Home Page of the site)############


    public function getRegister($post_watch_details="")
    {
		//print_r($post_watch_details);
		//exit;
    	//echo Config::get('app.locale_prefix');
    	//echo App::getLocale();
    	return view('frontend.account.register',compact('post_watch_details'));
    }
	
	function getAuthenticateLogin() 
	{
		return view('frontend.account.login');
	}
	
	
	function postAuthenticateLogin()
	{
		$data = Request::all();
		
		$site_user = SiteUser::where('email','=',Request::input('email'))->first();

		if($site_user != NULL)
		{
			if(Hash::check(Request::input('password'),$site_user->password))
			{
				if($site_user->status == 1)
				{
					//echo "user id = ".$site_user->id;
					Session::put('user_id', $site_user->id);
					//echo "-----".$user_id = Session::get('user_id');
					$response_message = Session::get('user_id');

					/* Storing post watch info into advert table (If a user try to post a watch without login) */
					if(!empty($data['post_watch_info'])){

						//$watch_details = $data['post_watch_info'];
						/*echo "<pre>";
						print_r($watch_details);
						exit;*/
						$watch = Adverts::create([
	            			'user_id'=>$site_user->id,
	            			'user_type'=>'U',
	                        'model_eng'=>$data['model_name'],
	                        'model_arabic'=>$data['model_name'],
	                        'model_num_eng'=>$data['model_number'],
	                        'model_num_arabic'=>$data['model_number'],
	                        'sell_price_eng'=>$data['sell_price'],
	                        'sell_price_arabic'=>$data['sell_price'],
	                        'contact_number'=>$data['contact_number'],
	                        'brand_id'=>$data['brand_id'],
	                        'gender_id'=>$data['gender_id'],
	                        'city_id'=>$data['city_id'],
	                        'currency_id'=>$data['currency_id'],
	                        'age_id'=>$data['age_id'],
	                        'material_id'=>$data['material_id'],
	                        'size_id'=>$data['size_id'],
	                        'bracelet_id'=>$data['bracelet_id'],
	                        'condition_id'=>$data['condition_id'],
	                        'style_id'=>$data['style_id'],
	                        'dialcolor_id'=>$data['dialcolor_id'],
	                        'boxpaper_id'=>$data['boxpaper_id'],
	                        'description_eng'=>htmlentities($data['description']),
	                        'description_arabic'=>htmlentities($data['description']),
                        ]);
			            $lastInsertedId = $watch->id;

			            $temp_file = Request::input('temp_file'); 
			            $temp_file_arr = explode(",",$temp_file);
			            $temp_file_arr = array_filter($temp_file_arr);  
			            
			            if(!empty($temp_file_arr))
			            {
			                foreach($temp_file_arr as $temp_file_list)
			                {
			                    if($temp_file_list!=''){
			                        $image   = AdvertImage::create([
			                                'advert_id' => $lastInsertedId,
			                                'image'     => $temp_file_list
			                        ]);
			                    }
			                    
			                }
			            }
			            $response_token = "SUCCESS@@".$lastInsertedId;
					}
					else{
						$response_token = "SUCCESS";
					}
					/*--------------------------------------*/
					
					//return redirect(App::getLocale().'/dashboard');
					
				}
				else
				{
					if($site_user->remember_token != "")
					{

						$response_message = 'Your account is not activated.You need to activate your account from the activation link sent to your mail.';
						if(App::getLocale()=='ar')
							$response_message = 'حسابك ليس activated.You ضرورة تفعيل حسابك من رابط التفعيل إرسالها إلى البريد الخاص بك.';

						$response_token = "FAILURE";
						//return redirect(App::getLocale().'/authenticate-login');
					}
					else
					{
						$response_message = 'Your account is inactivated by admin.';
						if(App::getLocale()=='ar')
							$response_message = 'هو المعطل حسابك من قبل المشرف';

						$response_token = "FAILURE";
						//return redirect(App::getLocale().'/authenticate-login');						
					}
				}
			}
			else
			{
				$response_message = 'Invalid Email/Password!!';
				if(App::getLocale()=='ar')
					$response_message = 'البريد الإلكتروني غير صالح / كلمة المرور';

					$response_token = "FAILURE";
				//return redirect(App::getLocale().'/authenticate-login');	
			}
		}
		else
		{
			$response_message = 'Invalid Email/Password!!';
			if(App::getLocale()=='ar')
				$response_message = 'البريد الإلكتروني غير صالح / كلمة المرور';

				$response_token = "FAILURE";
			//return redirect(App::getLocale().'/authenticate-login');	
		}
		echo $response_message.'@@'.$response_token;
		exit;
		//return view('frontend.user.dashboard.signin_businessowner');
	}
	
	function getSetSession($user_id="",$sell_watch="",$advert_id=""){
		//echo $user_id;
		
		//echo base64_decode($user_id);
		$user_id = base64_decode($user_id);
		//Session::put('user_id', $user_id);
		if($sell_watch==0){
			return redirect(App::getLocale().'/payment-mode/'.base64_encode($advert_id));
		}
		else{
			return redirect(App::getLocale());
		}
		
		//exit;
	}
    function postRegister() 
	{
		$data = Request::all();
		/*echo "<pre>";
		print_r($data);
		exit;*/
		if(Request::isMethod('post'))
		{
			$remember_token = uniqid();
			/***************** ADD SELLER *****************/
			
			$user = SiteUser::create([
									'name' 			=> $data['name'],
									'email' 		=> $data['seller_email'],
									'password' 		=> Hash::make($data['new_password']),
									'contact' 		=> $data['mobile_no'],
									'status'		=> '0',
									'remember_token'=> $remember_token
			]);
			$insertedId = $user->id;
			/* Storing post watch info into advert table (If a user try to post a watch without login) */
			if($data['post_watch_info']!=''){

				Session::put('user_id', $insertedId);

				$user = SiteUser::where('id',$insertedId)
								->update([
							'status'		=> '1'
						]);
				//$watch_details = $data['post_watch_info'];
				/*echo "<pre>";
				print_r($watch_details);
				exit;*/
				$watch = Adverts::create([
        			'user_id'=>$insertedId,
        			'user_type'=>'U',
                    'model_eng'=>$data['model_name'],
                    'model_arabic'=>$data['model_name'],
                    'model_num_eng'=>$data['model_number'],
                    'model_num_arabic'=>$data['model_number'],
                    'sell_price_eng'=>$data['sell_price'],
                    'sell_price_arabic'=>$data['sell_price'],
                    'contact_number'=>$data['contact_number'],
                    'brand_id'=>$data['brand_id'],
                    'gender_id'=>$data['gender_id'],
                    'city_id'=>$data['city_id'],
                    'currency_id'=>$data['currency_id'],
                    'age_id'=>$data['age_id'],
                    'material_id'=>$data['material_id'],
                    'size_id'=>$data['size_id'],
                    'bracelet_id'=>$data['bracelet_id'],
                    'condition_id'=>$data['condition_id'],
                    'style_id'=>$data['style_id'],
                    'dialcolor_id'=>$data['dialcolor_id'],
                    'boxpaper_id'=>$data['boxpaper_id'],
                    'description_eng'=>htmlentities($data['description']),
                    'description_arabic'=>htmlentities($data['description']),
                ]);
	            $lastInsertedId = $watch->id;

	            $temp_file = Request::input('temp_file'); 
	            $temp_file_arr = explode(",",$temp_file);
	            $temp_file_arr = array_filter($temp_file_arr);  
	            
	            if(!empty($temp_file_arr))
	            {
	                foreach($temp_file_arr as $temp_file_list)
	                {
	                    if($temp_file_list!=''){
	                        $image   = AdvertImage::create([
	                                'advert_id' => $lastInsertedId,
	                                'image'     => $temp_file_list
	                        ]);
	                    }
	                    
	                }
	            }
	            return redirect(App::getLocale().'/payment-mode/'.base64_encode($lastInsertedId));
	            exit;
			}
			/*--------------------------------------*/
			/**************************SEND MAIL -start *****************************/
				
				$site = Sitesetting::where(['name' => 'email'])->first();
				$admin_users_email = $site->value;
				
				$users = SiteUser::where("id","=",$insertedId)->first();
				$user_name = $users->name.' '.$users->last_name;
				$user_email = $users->email;
				
				$subject = "Your Account has been created";
				$message_body = "Your Account has been created in ".env('APP_NAME').".<a href='".url()."/".App::getLocale()."/active-account/".$remember_token."'>Click Here</a> to active your account.";
				
				
				$mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
				{
					$message->from($admin_users_email,env('APP_NAME'));
		
					$message->to($user_email)->subject($subject);
				});
				
			/**************************SEND MAIL -start *****************************/	
			
			Session::flash('success_message', 'You have successfully registered.Please check your mail and activate your account.');
			return redirect(App::getLocale().'/register');
		}
	}
	
	
	public function getActivateAccount($remember_code)
	{
		
		$site_user = SiteUser::where("remember_token","=",$remember_code)->first();
		$site_user_count = SiteUser::where("remember_token","=",$remember_code)->count();
		if($site_user_count>0)
		{
			$user = SiteUser::find($site_user->id);
			$user->status = '1';
			$user->remember_token = '';
			$user->save();
			Session::flash('success_message', 'Your account has been activated.');
		    return redirect(App::getLocale().'/register');
		}
		else
		{
			Session::flash('failure_message', 'The link is expired.');
		    return redirect(App::getLocale().'/register');
		}
	}
	
	public function getForgotPassword()
	{
		return view('frontend.account.forgot_password');
	}
	
	public function postForgotPassword()
	{
		
		$data = Request::all();
		$email = $data['forgot_email'];
		
		$is_user_exists = SiteUser::where('email','=',$email)
								->count();
		if($is_user_exists == 0)
		{
			$session_message = 'Email not exists.';
			if(App::getLocale()=='ar')
				$session_message = 'البريد الإلكتروني غير موجود';
				
			Session::flash('failure_message', $session_message);
			return redirect(App::getLocale().'/forgot-password');
		}
		else
		{
			
			/**************************SEND MAIL -start *****************************/
			
			$site = Sitesetting::where(['name' => 'email'])->first();
			$admin_users_email = $site->value;
			
			
			$reset_password_key = $this->get_unique_alphanumeric_no(16);
			SiteUser::where('email','=',$email)
						->update(['reset_password_key'=>$reset_password_key]);


			$user_detls = SiteUser::where('email','=',$email)->first();
			
			/***************** USER MAIL ******************/
			
			$user_name = $user_detls->name.' '.$user_detls->last_name;
			$user_email = $user_detls->email;
			
			$subject = "Reset Password";
			$message_body = "<a href='".url()."/".App::getLocale()."/reset-password/".base64_encode($reset_password_key)."'>Click Here to reset your password.</a>";
			
			
			$mail = Mail::send(['html' => 'frontend.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
			{
				$message->from($admin_users_email,env('APP_NAME'));
	
				$message->to($user_email)->subject($subject);
			});
			
			/************************** SEND MAIL -end *****************************/
			$session_message = 'Please check your mail to reset password.';
			if(App::getLocale()=='ar')
				$session_message = 'يرجى التحقق من البريد الإلكتروني لإعادة تعيين كلمة المرور.';
			 
			Session::flash('success_message',$session_message);
			
			return redirect(App::getLocale().'/forgot-password');

			
		}

		return view('frontend.account.forgot_password');
	}
	
	public function resetPassword($reset_password_key = '')
	{
		
		//$is_valid_link = 0;

		$reset_password_key = base64_decode($reset_password_key);
		
		$user_arr = SiteUser::where("reset_password_key",$reset_password_key)->first();
		
		$user_arr_count = SiteUser::where("reset_password_key",$reset_password_key)->count();
		
		if($user_arr_count>0)
		{
			$email = $user_arr->email;
			
			if(Request::isMethod('post'))
			{
				$data = Request::all();
				//Hash::make($data['password']),
				SiteUser::where('reset_password_key','=',$reset_password_key)
							->update([
								'reset_password_key'	=>	'',
								'password'	=>	Hash::make($data['new_password']),
							]);
							
				$session_message = 'Password has been set successfully.';
				if(App::getLocale()=='ar')
					$session_message = 'تم تعيين كلمة المرور بنجاح';
					
				Session::flash('success_message', $session_message);
				return redirect(App::getLocale());
			}
			$is_user_exists = SiteUser::where('email','=',$email)
						->where('reset_password_key','=',$reset_password_key)
						->first();
			if($is_user_exists != null)
			{
				$is_valid_link = 1;
			}
			else
			{
				$is_valid_link = 0;
			}
			return view('frontend.account.reset_password',compact('is_valid_link'));
		}
		else
		{
			$session_message = 'The url has been expired.';
				if(App::getLocale()=='ar')
					$session_message = 'وقد انتهت رابط';
			Session::flash('failure_message', $session_message);
			return redirect(App::getLocale());
		}
	}
	function getCheckEmailAvailability()
	{
		$data = Request::all();
		$email = $data['seller_email'];
		$user_arr_count = SiteUser::where("email","=",$email)->get()->count();
		if($user_arr_count>0){
			echo "false";
		}
		else{
			echo "true";
		}
		exit();
	}

}