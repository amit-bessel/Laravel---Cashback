<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/
// Define Model
use App\Model\Brandmember; /* Model name*/
use App\Model\Newsletter;  /* Model name*/
use App\Model\Blog;  /* Model name*/
use App\Model\Country;/*Model Name*/
use App\Model\Zone;/*Model Name*/
use App\Model\City;/*Model Name*/
use App\Model\Sitesetting;/*Model Name*/
use App\Model\Invitefriend;/*Model Name*/
use App\Model\SiteUser;/*Model Name*/
use App\Model\Adverts; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\Gender; /* Model name*/
use App\Model\Banner; /* Model name*/
use App\Model\Age; /* Model name*/
use App\Model\Material; /* Model name*/
use App\Model\Size; /* Model name*/
use App\Model\Bracelet; /* Model name*/
use App\Model\Condition; /* Model name*/
use App\Model\Style; /* Model name*/
use App\Model\Dialcolor; /* Model name*/
use App\Model\Boxpaper; /* Model name*/
use App\Model\Currency; /* Model name*/
use App\Model\Post; /* Model name*/
use App\Model\AdvertImage; /* Model name*/
use App\Model\Favourite; /* Model name*/
use App\Model\PaymentHistory; /* Model name*/
use App\Model\OrderHistory; /* Model name*/
use App\Model\WithdrawalHistory; /* Model name*/
use App\Model\Userrefer; /* Model name*/
use App\Model\Unsubscribe; /* Model name*/
use App\Http\Requests;
use App\Http\Controllers\Controller;    
use Illuminate\Support\Facades\Request;
use App\Model\SiteUserReferId;/* Model name*/
use App\Model\Tangoorder;/* Model name*/
use App\Model\Walletdetails;/* Model name*/

use App\Model\Withdrawldetails;/* Model name*/

use App\Model\Giftcard;/* Model name*/
use App\Model\Giftcarddetail;/* Model name*/

use Mail;
use Input; /* For input */
use Validator;
use Session;
use Customhelpers;
use Intervention\Image\Facades\Image; // Use this if you want facade style code

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
use App\Model\Emailnotification; /* Model name*/
use App\Model\Emailnotification_Siteuser; /* Model name*/

class UserController extends BaseController {
    public function __construct() 
    {
        parent::__construct();

        $obj = new helpers();
        view()->share('obj',$obj);
		if(App::getLocale()=='en')
            $table_column_lang_suffix = 'eng';
        if(App::getLocale()=='ar')
            $table_column_lang_suffix = 'arabic';
       view()->share('table_column_lang_suffix',$table_column_lang_suffix);
       $active_menu = "";
       view()->share('active_menu',$active_menu);
    }
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
	function getLogout()
	{
        echo 1;exit;
		Session::flush();
		return redirect(App::getLocale());
	}

    function getIndex()
    {
        echo 1;exit;
        Session::flush();
        return redirect(App::getLocale());
    }
	
    /**************User Dashboard********************/

	public function getDashboard()
	{
		

		//$user_details = $this->authenticateUser();

        if (Session::has('user_id')){

        /****************Get user details ******************************/



        $userprofileheadinfo=Customhelpers::getUserDetails();
        $id = Session::get('user_id');

        $walletdetails_count=Walletdetails::where('siteusers_id',$id)->count();

        if($walletdetails_count==0){

            return redirect('user/mydashboard');
        }

        $pendinginvite = Invitefriend::where('siteusers_id',$id)->where('status',0)->get();
        $registerinvite = Userrefer::with("userreferlink1")->where('referby',$id)->where('usertype',0)->get();
        if(count($pendinginvite)>0){
            $pendinginvite = $pendinginvite->toArray();
        }
        if(count($registerinvite)>0){
            $registerinvite = $registerinvite->toArray();
        }
     /*   echo '<pre>';
        print_r($registerinvite);
        exit();*/
        $total_Commission = Walletdetails::selectRaw("sum(amount) as amount")
                        ->where('siteusers_id','=', $id)
                        ->where("status","=","1") 
                        ->where("purpose_state","=", '2')  // commission
                        ->where('type', '=', 1)
                        ->first();


		return view('frontend.user.dashboard',compact('userprofileheadinfo','registerinvite','total_Commission'));
       }
       else{

        return redirect('login');
      }
	}


    public function getUserEarn(){
        if (Session::has('user_id')){
            $user_id = Session::get('user_id');
            $now = date('Y-m-1');
            $last = date('Y-m-t');
            $prevMonthStart = date('Y-m-d', strtotime("first day of last month"));
            $prevMonthLast =  date('Y-m-d 11:59:59', strtotime("last day of last month"));
            $prevMonthLastday =  date('d', strtotime("last day of last month"));
            $prevMonth = date('m', strtotime("last day of last month"));
            /*echo $prevMonth;
            exit();*/
            $from = date('Y-m-d 00:00:00', strtotime('-7 days'));
            $to = date('Y-m-d 11:59:59', strtotime('-1 days'));
            $new_user = Walletdetails::selectRaw("created_at as day,sum(amount) as amount")
                        ->where('siteusers_id','=', $user_id)
                        ->where("status","=","1") 
                        ->whereIn("purpose_state", ['1', '2','5'])  // commission
                        // ->orwhere("purpose_state","=", 3) // cashback
                        ->where('created_at', '>=', $from)
                        ->where('created_at', '<=', $to)
                        ->where('type', '=', 1)
                        ->where('walletstatus', '=', '1')
                        ->groupBy("created_at")
                        ->get();



            $result_earn = array();
            if(!empty($new_user)){
                for ($i = 7; $i>=1 ; $i--) {
                    $dt = date('Y-m-d', strtotime('-'.$i.' days'));
                    $flag =0;
                    foreach ($new_user as $key2 => $value2) {
                        $value2['day'] = date('Y-m-d', strtotime($value2['day']));    
                        if($value2['day'] == $dt){
                            $result_earn['dat'][] = $dt;
                            $result_earn['amt'][] = $value2->amount;
                            $flag = 1;
                        }
                    }
                    if($flag == 0){
                        $result_earn['dat'][] = $dt;
                        $result_earn['amt'][] = 0;
                    }
                }
            }
            else{
                for($i=7;$i>=1;$i--){
                    $dt = date('Y-m-d', strtotime('-'.$i.' days'));
                    $result_earn['dat'][] = $dt;
                    $result_earn['amt'][] = 0;
                }
            }


            $prev_month_earn = Walletdetails::selectRaw("created_at as day,sum(amount) as amount")
                        ->where('siteusers_id','=', $user_id)
                        ->where("status","=","1") 
                        ->whereIn("purpose_state", ['1', '2','5'])  //sign up credit
                        // ->orwhere("purpose_state","=", 2)  // commission
                        // ->orwhere("purpose_state","=", 3) // cashback
                        ->where('created_at', '>=', $prevMonthStart)
                        ->where('created_at', '<=', $prevMonthLast)
                        ->where('type', '=', 1)
                        ->where('walletstatus', '=', '1')
                        ->groupBy("created_at")
                        ->get();
                        /*echo '<pre>';
                        print_r($prev_month_earn);
                        exit;*/

            $result_prev_month = array();
            if(!empty($prev_month_earn)){
                for ($i = 1; $i<=$prevMonthLastday ; $i++) {
                    $dt = date('Y-'.$prevMonth.'-'.str_pad($i, 2, "0", STR_PAD_LEFT));
                    $flag =0;
                    foreach ($prev_month_earn as $key2 => $value2) {
                        $value2['day'] = date('Y-m-d', strtotime($value2['day']));    
                        if($value2['day'] == $dt){
                            $result_prev_month['dat'][] = $dt;
                            $result_prev_month['amt'][] = $value2->amount;
                            $flag = 1;
                        }
                    }
                    if($flag == 0){
                        $result_prev_month['dat'][] = $dt;
                        $result_prev_month['amt'][] = 0;
                    }
                }
            }
            else{
                for($i=1;$i<=$prevMonthLastday;$i++){
                    $dt = date('Y-'.$prevMonth.'-'.str_pad($i, 2, "0", STR_PAD_LEFT));
                    $result_prev_month['dat'][] = $dt;
                    $result_prev_month['amt'][] = 0;
                }
            }

            $prev_month_save = Walletdetails::selectRaw("created_at as day,sum(amount) as amount")
                        ->where('siteusers_id','=', $user_id)
                        ->where("status","=","1") 
                        /*->where("purpose_state","=", 1)  //sign up credit
                        ->orwhere("purpose_state","=", 2)  // commission*/
                        ->where("purpose_state","=", '3') // cashback
                        ->where('created_at', '>=', $prevMonthStart)
                        ->where('created_at', '<=', $prevMonthLast)
                        ->where('type', '=', 1)
                        ->where('walletstatus', '=', '1')
                        ->groupBy("created_at")
                        ->get();

            $result_prev_month_save = array();
            if(!empty($prev_month_save)){
                for ($i = 1; $i<=$prevMonthLastday ; $i++) {
                    $dt = date('Y-'.$prevMonth.'-'.str_pad($i, 2, "0", STR_PAD_LEFT));
                    $flag =0;
                    foreach ($prev_month_save as $key2 => $value2) {
                        $value2['day'] = date('Y-m-d', strtotime($value2['day']));    
                        if($value2['day'] == $dt){
                            $result_prev_month_save['amt'][] = $value2->amount;
                            $flag = 1;
                        }
                    }
                    if($flag == 0){
                        $result_prev_month_save['amt'][] = 0;
                    }
                }
            }
            else{
                for($i=1;$i<=$prevMonthLastday;$i++){
                    $dt = date('Y-'.$prevMonth.'-'.str_pad($i, 2, "0", STR_PAD_LEFT));
                    $result_prev_month_save['amt'][] = 0;
                }
            }



                       
            $total_Commission = Walletdetails::selectRaw("sum(amount) as amount")
                        ->where('siteusers_id','=', $user_id)
                        ->where("status","=","1") 
                        ->whereIn("purpose_state", ['2','5'])  // commission
                        ->where('type', '=', 1)
                        ->where('walletstatus', '=', '1')
                        ->first();

            $total_save =  Walletdetails::selectRaw("sum(amount) as amount")
                        ->where('siteusers_id','=', $user_id)
                        ->where("status","=","1") 
                        ->where("purpose_state","=", '3')  // cashback
                        ->where('type', '=', 1)
                        ->where('walletstatus', '=', '1')
                        ->first();
            $result = array();
            $result['total_earn'] = $result_earn;
            $result['prev_month_earn'] = $result_prev_month;
            $result['prev_month_save'] = $result_prev_month_save;
            $result['total_commission'] = $total_Commission['amount'];
            $result['total_save'] = $total_save['amount'];
            echo json_encode($result);
        }
    }



    public function getMyDashboard()
    {
        

        //$user_details = $this->authenticateUser();

        if (Session::has('user_id')){

        /****************Get user details ******************************/

        $userprofileheadinfo=Customhelpers::getUserDetails();

        return view('frontend.user.mydashboard',compact('userprofileheadinfo'));
       }
       else{

        return redirect('login');
      }
    }
	
	/*UUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUU*/	
	/*							CHANGE PASSWORD							 */
	/*-------------------------------------------------------------------*/
	public function changePassword()
	{
		$user_details = $this->authenticateUser();
		//echo "<pre>";
		//print_r($user_details);
		//exit;
			
		if(Request::isMethod('post'))
		{
			$data = Request::all();
			//echo "<pre>";
			//print_r($data);
			//exit;
			if(Hash::check($data['old_password'],$user_details->password))
			{
				$user_details->password = Hash::make($data['new_password']);
				$user_details->save();		
				$session_message = 'Password has been changed successfully.';
				if(App::getLocale()=='ar')
					$session_message = 'تم تغيير كلمة السر بنجاح';
					
				Session::flash('success_message', $session_message);
				return redirect(App::getLocale().'/change-password');
			}
			else{
				$session_message = 'Invalid old password.';
				if(App::getLocale()=='ar')
					$session_message = 'كلمة المرور القديمة غير صالحة';
					
				Session::flash('failure_message', $session_message);
				return redirect(App::getLocale().'/change-password');
			}
			
		}
		return view('frontend.user.change_password');
		
	}
	/*------------------	CHNAGE PASSWORD END	-------------------------*/
	
	
	/*UUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUU*/	
	/*							USER PROFILE							 */
	/*-------------------------------------------------------------------*/
	public function getProfile(){
		
		$user_details = $this->authenticateUser();
		return view('frontend.user.profile',compact('user_details'));
	}
	
	public function postProfile(){
		
		$user_details = $this->authenticateUser();
		$data = Request::all();
		$user_details->name			= $data['name'];
		$user_details->email		= $data['seller_email'];
		$user_details->contact		= $data['mobile_no'];
		$user_details->save();
		
		$session_flash_msg = 'Your profile has been updated successfully.';
		if(App::getLocale()=='ar')
			$session_flash_msg = 'تم تحديث ملف التعريف الخاص بك بنجاح';
			
		Session::flash('success_message', $session_flash_msg);
		return redirect(App::getLocale().'/profile');	
	}
    
	/*------------------	USER PROFILE END	-------------------------*/	

	function getEmailAvailability()
	{
		$user_details = $this->authenticateUser();
		$data = Request::all();
		$email = $data['seller_email'];
		$user_arr_count = SiteUser::where("email","=",$email)->where('id','!=',$user_details->id)->get()->count();
		if($user_arr_count>0){
			echo "false";
		}
		else{
			echo "true";
		}
		exit();
	}


    public function getSellWatch()
    {
    	//$user_details = $this->authenticateUser();
        $brand_arr        = Brand::where('is_active', '=', 1)->orderBy('id','ASC')->get();
        $gender_arr       = Gender::where('is_active', '=', 1)->orderBy('id','ASC')->get();
        $city_arr         = City::where('is_active', '=', 1)->orderBy('id','ASC')->get();
        $age_arr          = Age::where('is_active', '=', 1)->orderBy('id','ASC')->get();
        $material_arr     = Material::where('is_active', '=', 1)->orderBy('id','ASC')->get();
        $size_arr         = Size::where('is_active', '=', 1)->orderBy('id','ASC')->get();
        $bracelet_arr     = Bracelet::where('is_active', '=', 1)->orderBy('id','ASC')->get();
        $condition_arr    = Condition::where('is_active', '=', 1)->orderBy('id','ASC')->get();
        $style_arr        = Style::where('is_active', '=', 1)->orderBy('id','ASC')->get();
        $dial_arr         = Dialcolor::where('is_active', '=', 1)->orderBy('id','ASC')->get();
        $boxpaper_arr     = Boxpaper::where('is_active', '=', 1)->orderBy('id','ASC')->get();
        $currency_arr     = Currency::where('is_active', '=', 1)->orderBy('id','ASC')->get();

        return view('frontend.user.sell_watch',compact('module_head','advert_class','brand_arr','gender_arr','city_arr','age_arr','material_arr','size_arr','bracelet_arr','condition_arr','style_arr','dial_arr','boxpaper_arr','currency_arr'));
        //return view('frontend.home.sell_watch');
    }


    public function postAddWatch()
    {
    	$user_details = $this->authenticateUser();
        if(Request::isMethod('post'))
        {
            $watch_details = Request::all();


            $temp_file = Request::input('temp_file'); 
            $temp_file_arr = explode(",",$temp_file);
            $temp_file_arr = array_filter($temp_file_arr);  
            
            // if(!empty($temp_file_arr))
            // {
            //     $m=0;
            //     foreach($temp_file_arr as $temp_file_list)
            //     {
            //         // File and rotation
            //         $filename = 'uploads/watch_image/'.$temp_file_list;
            //         $degrees = $watch_details['temp_file_flip'][$m];

            //         // Your original file
            //         $original   =   imagecreatefromjpeg($filename);
            //         // Rotate
            //         $rotated    =   imagerotate($original, $degrees, 0);
                   
            //         // Save to a directory with a new filename
            //         imagejpeg($rotated,$filename);

            //         // Standard destroy command
            //         imagedestroy($rotated);


                    

            //         // Content type
            //        // header('Content-type: image/jpeg');

            //         /*// Load
            //         $source = imagecreatefromjpeg($filename);

            //         // Rotate
            //         $rotate = imagerotate($source, $degrees, 0);

            //         //header("Content-Type: application/force-download"); 
            //         //header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" ); 

            //         // Output
            //        // imagejpeg($rotate);

            //         // Free the memory
            //         imagedestroy($source);
            //         imagedestroy($rotate);*/

            //         $m++;
            //     }
            // }




            //echo "<pre>"; print_r($watch_details);exit;
            $watch = Adverts::create([
            			'user_id'=>$user_details->id,
            			'user_type'=>'U',
                        'model_eng'=>$watch_details['model_name'],
                        'model_arabic'=>$watch_details['model_name'],
                        'model_num_eng'=>$watch_details['model_number'],
                        'model_num_arabic'=>$watch_details['model_number'],
                        'sell_price_eng'=>$watch_details['sell_price'],
                        'sell_price_arabic'=>$watch_details['sell_price'],
                        'contact_number'=>$watch_details['contact_number'],
                        'brand_id'=>$watch_details['brand_id'],
                        'gender_id'=>$watch_details['gender_id'],
                        'city_id'=>$watch_details['city_id'],
                        'currency_id'=>$watch_details['currency_id'],
                        'age_id'=>$watch_details['age_id'],
                        'material_id'=>$watch_details['material_id'],
                        'size_id'=>$watch_details['size_id'],
                        'bracelet_id'=>$watch_details['bracelet_id'],
                        'condition_id'=>$watch_details['condition_id'],
                        'style_id'=>$watch_details['style_id'],
                        'dialcolor_id'=>$watch_details['dialcolor_id'],
                        'boxpaper_id'=>$watch_details['boxpaper_id'],
                        'description_eng'=>htmlentities($watch_details['description']),
                        'description_arabic'=>htmlentities($watch_details['description']),
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

            $session_flash_msg = 'Watch has been added successfully.';
            if(App::getLocale()=='ar')
                $session_flash_msg = 'تمت إضافة وتش بنجاح';
                
            //Session::flash('success_message', $session_flash_msg);
            //return redirect(App::getLocale().'/my-watches');
            return redirect(App::getLocale().'/payment-mode/'.base64_encode($lastInsertedId));
        }
    }


    public function postUploadRotateImage()
    {
        /*echo "<pre>";
        print_r(Request::input('image_name'));
        print_r(Request::input('angle'));
        exit;*/


        $filename_big = 'uploads/watch_image/big/'.Request::input('image_name');
        $filename = 'uploads/watch_image/'.Request::input('image_name');
        $degrees = Request::input('angle');

        $ext = pathinfo(Request::input('image_name'), PATHINFO_EXTENSION);
        $advert_image_name = rand(111111111,999999999).'.'.$ext;
        $file_path_big = 'uploads/watch_image/big/'.$advert_image_name;
        $file_path = 'uploads/watch_image/'.$advert_image_name;

        


        // Your original file
        $original_big   =   imagecreatefromjpeg($filename_big);
        // Rotate
        $rotated_big    =   imagerotate($original_big, $degrees, 0);
        @@unlink($filename_big);
        // Save to a directory with a new filename
        imagejpeg($rotated_big,$file_path_big);

        // Your original file
        $original   =   imagecreatefromjpeg($filename);
        // Rotate
        $rotated    =   imagerotate($original, $degrees, 0);
        @@unlink($filename);
        // Save to a directory with a new filename
        imagejpeg($rotated,$file_path);

        // Standard destroy command
        //imagedestroy($rotated_big);
        //imagedestroy($rotated);

        //
        //

        echo $advert_image_name;
        exit();
        
    }



    /*UUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUU*/ 
    /*                          PAYMENT MODE                             */
    /*-------------------------------------------------------------------*/

    public function getPaymentMode($advert_id=""){
        /*echo "==========".$advert_id;
        exit;*/
        /********** Post watch amount .*************/
        $site_setting = Sitesetting::where('id',7)->first();

        $post_watch_price = $site_setting->value;
        $payment_mode_info = Sitesetting::where('id',8)->first();
        /*-----------------------------------------*/
        return view('frontend.user.payment_mode',compact('post_watch_price','advert_id','payment_mode_info'));
    }

    /*------------------    PAYMENT MODE END    -------------------------*/ 

    public function getCompletePayment($advert_id="", $payment_type="",$response_message=""){
        $advert_id = base64_decode($advert_id);
        $payment_type = base64_decode($payment_type);
        return view('frontend.user.success',compact('advert_id','payment_type','response_message'));
    }

    public function postUploadImage()
    {
        /*echo "<pre>";
        print_r($_FILES["file"]);
        exit;*/
        
        if (isset($_FILES["file"])) {
            
            $upload_folder = "uploads/watch_image/";
            
            $img_file_name = Input::file('file');
            
            
            $ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
            
            $image_type = array("jpg", "png", "gif", "jpeg");
           // $file_name = time().rand(1111,9999).'.'.$ext;
            
            if (in_array($ext, $image_type))
            {
                //$obj               = new helpers();
                $advert_image      = $img_file_name;
                //$destinationPath   = 'uploads/watch_image/';                
                $extension         = $advert_image->getClientOriginalExtension(); 
                $advert_image_name = rand(111111111,999999999).'.'.$extension;
                //$filename -> resize (300,300);
                //$advert_image->move($upload_folder, $advert_image_name);

                $big_image_path = 'uploads/watch_image/big/' . $advert_image_name;
                Image::make($advert_image->getRealPath())->resize(500, 550)->save($big_image_path);
                
                $thumb_image_path = 'uploads/watch_image/' . $advert_image_name;
                Image::make($advert_image->getRealPath())->resize(145, 230)->save($thumb_image_path);
                
                
                
                echo $advert_image_name;
            }
            else
            {
                echo "0";
            }
         }
         exit();
        
    }

    public function postDeleteTempImage($image_name)
    {
        @unlink("uploads/watch_image/".$image_name);
        echo "1";
        exit();
    }


	public function getMyWatches(){
		$user_details = $this->authenticateUser();
		/*echo "<pre>";
		print_r($user_details);
		exit;*/
		return view('frontend.user.my_watches');
	}

	public function getWatchLists(){
		$user_details = $this->authenticateUser();
        $data = Request::all();
       /* echo "<pre>";
        print_r($user_details);
        echo "<br />";
        exit;*/
        $offset = $data['offset'];
        $limit  = $data['limit'];
        /*######################### SERACH CONDITIONS #########################*/
        $where_raw = "";
        
        $where_raw .= "1=1";
        /************************ SERCH CONDITIONS END *************************/
       
        $order_by_raw = "`adverts`.`created_at` DESC";
            
        /*############################ SORTING PRODUCT ########################*/

        /*************************** SORTING PRODUCT END ***********************/

        /**/
        $product_info = Adverts::with('advert_images')
                        ->join('cities', 'adverts.city_id', '=', 'cities.id')
                        ->join('brands', 'adverts.brand_id', '=', 'brands.id')
                        ->join('sizes', 'adverts.size_id', '=', 'sizes.id')
                        ->join('age', 'adverts.age_id', '=', 'age.id')
                        ->join('gender', 'adverts.gender_id', '=', 'gender.id')
                        ->join('materials', 'adverts.material_id', '=', 'materials.id')
                        ->join('bracelets', 'adverts.bracelet_id', '=', 'bracelets.id')
                        ->join('conditions', 'adverts.condition_id', '=', 'conditions.id')
                        ->join('boxpapers', 'adverts.boxpaper_id', '=', 'boxpapers.id')
                        ->join('styles', 'adverts.style_id', '=', 'styles.id')
                        ->join('dialcolor', 'adverts.dialcolor_id', '=', 'dialcolor.id')
                        ->join('currency', 'adverts.currency_id', '=', 'currency.id')
                        ->whereRaw($where_raw)
                        ->whereRaw('`adverts`.`user_id` = '.$user_details->id)
                        ->whereRaw('`adverts`.`user_type` = "U"')
                        ->select('adverts.*','currency.currency_eng','currency.currency_arabic')
                        ->orderByRaw($order_by_raw)
                        ->skip($offset)
                        ->take($limit)
                        ->get();

        $no_of_product = Adverts::with('advert_images')
                        ->join('cities', 'adverts.city_id', '=', 'cities.id')
                        ->join('brands', 'adverts.brand_id', '=', 'brands.id')
                        ->join('sizes', 'adverts.size_id', '=', 'sizes.id')
                        ->join('age', 'adverts.age_id', '=', 'age.id')
                        ->join('gender', 'adverts.gender_id', '=', 'gender.id')
                        ->join('materials', 'adverts.material_id', '=', 'materials.id')
                        ->join('bracelets', 'adverts.bracelet_id', '=', 'bracelets.id')
                        ->join('conditions', 'adverts.condition_id', '=', 'conditions.id')
                        ->join('boxpapers', 'adverts.boxpaper_id', '=', 'boxpapers.id')
                        ->join('styles', 'adverts.style_id', '=', 'styles.id')
                        ->join('dialcolor', 'adverts.dialcolor_id', '=', 'dialcolor.id')
                        ->join('currency', 'adverts.currency_id', '=', 'currency.id')
                        ->whereRaw($where_raw)
                        ->whereRaw('`adverts`.`user_id` = '.$user_details->id)
                        ->whereRaw('`adverts`.`user_type` = "U"')
                        ->select('adverts.*','currency.currency_eng','currency.currency_arabic')
                        ->orderByRaw($order_by_raw)
                        ->count();
        //echo "<pre>";
       
        if(count($product_info)>0){
            $product_info = $product_info->toArray();
        }
        /*echo "<pre>";
        print_r($product_info);
        exit;*/
        //$no_of_product = count($product_info);
       
        //compact('product_info','no_of_product','offset','limit')
        return view('frontend.user.watch_list',compact('product_info','no_of_product','offset','limit'));
    }

    public function getMarkAsSold($product_id=""){
        $user_details = $this->authenticateUser();
        //echo $product_id;
        $mark_as_sold = Adverts::where('id',$product_id)
                                ->update(['is_sold'=>1]);
        echo 1;
        exit;
    }


    public function getMyFavourites(){
        $user_details = $this->authenticateUser();
        /*echo "<pre>";
        print_r($user_details);
        exit;*/
        return view('frontend.user.my_favourite');
    }

    public function getFavourites(){
        $user_details = $this->authenticateUser();
        $data = Request::all();
       /* echo "<pre>";
        print_r($user_details);
        echo "<br />";
        exit;*/
        $offset = $data['offset'];
        $limit  = $data['limit'];
        /*######################### SERACH CONDITIONS #########################*/
        $where_raw = "";
        
        $where_raw .= "1=1";
        /************************ SERCH CONDITIONS END *************************/
       
        $order_by_raw = "`adverts`.`created_at` DESC";
            
        /*############################ SORTING PRODUCT ########################*/

        /*************************** SORTING PRODUCT END ***********************/

        /**/
        $product_info = Adverts::with('advert_images')
                        ->join('favourites', 'adverts.id', '=', 'favourites.product_id')
                        ->join('cities', 'adverts.city_id', '=', 'cities.id')
                        ->join('brands', 'adverts.brand_id', '=', 'brands.id')
                        ->join('sizes', 'adverts.size_id', '=', 'sizes.id')
                        ->join('age', 'adverts.age_id', '=', 'age.id')
                        ->join('gender', 'adverts.gender_id', '=', 'gender.id')
                        ->join('materials', 'adverts.material_id', '=', 'materials.id')
                        ->join('bracelets', 'adverts.bracelet_id', '=', 'bracelets.id')
                        ->join('conditions', 'adverts.condition_id', '=', 'conditions.id')
                        ->join('boxpapers', 'adverts.boxpaper_id', '=', 'boxpapers.id')
                        ->join('styles', 'adverts.style_id', '=', 'styles.id')
                        ->join('dialcolor', 'adverts.dialcolor_id', '=', 'dialcolor.id')
                        ->join('currency', 'adverts.currency_id', '=', 'currency.id')
                        ->whereRaw($where_raw)
                        ->whereRaw('`favourites`.`user_id` = '.$user_details->id)
                        ->whereRaw('`adverts`.`user_type` = "U"')
                        ->select('adverts.*','currency.currency_eng','currency.currency_arabic')
                        ->orderByRaw($order_by_raw)
                        ->skip($offset)
                        ->take($limit)
                        ->get();

        $no_of_product = Adverts::with('advert_images')
                        ->join('favourites', 'adverts.id', '=', 'favourites.product_id')
                        ->join('cities', 'adverts.city_id', '=', 'cities.id')
                        ->join('brands', 'adverts.brand_id', '=', 'brands.id')
                        ->join('sizes', 'adverts.size_id', '=', 'sizes.id')
                        ->join('age', 'adverts.age_id', '=', 'age.id')
                        ->join('gender', 'adverts.gender_id', '=', 'gender.id')
                        ->join('materials', 'adverts.material_id', '=', 'materials.id')
                        ->join('bracelets', 'adverts.bracelet_id', '=', 'bracelets.id')
                        ->join('conditions', 'adverts.condition_id', '=', 'conditions.id')
                        ->join('boxpapers', 'adverts.boxpaper_id', '=', 'boxpapers.id')
                        ->join('styles', 'adverts.style_id', '=', 'styles.id')
                        ->join('dialcolor', 'adverts.dialcolor_id', '=', 'dialcolor.id')
                        ->join('currency', 'adverts.currency_id', '=', 'currency.id')
                        ->whereRaw($where_raw)
                        ->whereRaw('`favourites`.`user_id` = '.$user_details->id)
                        ->whereRaw('`adverts`.`user_type` = "U"')
                        ->select('adverts.*','currency.currency_eng','currency.currency_arabic')
                        ->orderByRaw($order_by_raw)
                        ->count();
        //echo "<pre>";
       
        if(count($product_info)>0){
            $product_info = $product_info->toArray();
        }
        /*echo "<pre>";
        print_r($product_info);
        exit;*/
        //$no_of_product = count($product_info);
       
        //compact('product_info','no_of_product','offset','limit')
        return view('frontend.user.favourite_list',compact('product_info','no_of_product','offset','limit'));
    }

    public function getAddToFavourite(){
        $user_details = $this->authenticateUser();
        /*echo "<pre>";
        print_r($user_details);
        exit;*/
        $data = Request::all();
        /*echo "<pre>";
        print_r($data);*/
        $check_favourite = Favourite::where('user_id',$user_details->id)->where('product_id',$data['product_id'])->get();
        if(count($check_favourite)>0){
            echo 0;
        }
        else{
            $add_to_favourite = Favourite::create(array('user_id'=>$user_details->id,'product_id'=>$data['product_id']));
            $total_favourite  = Adverts::where('id',$data['product_id'])->increment('total_favourite', 1);
            echo 1;
        }
        exit;
        //return view('frontend.user.my_favourite');
    }

    public function getRemoveFavourite($advert_id=""){
        //echo "==============".$advert_id;
        $user_details = $this->authenticateUser();
        $advert_id  = base64_decode($advert_id);
        Favourite::where('user_id',$user_details->id)->where('product_id',$advert_id)->delete();
        $session_flash_msg = 'Successfully removed from favourite listing.';
        if(App::getLocale()=='ar')
            $session_flash_msg = 'تمت إضافة وتش بنجاح';
            
        Session::flash('success_message', $session_flash_msg);
        return redirect(App::getLocale().'/my-favourites');
        //exit;
    }

    public function getPaymentHistory()
    {
        //Session::put('seller_id11', 10);

        $user_details = $this->authenticateUser();
        return view('frontend.user.payment_history');
    }


    public function getPaymentHistoryList(){
        $user_details = $this->authenticateUser();
        $data = Request::all();
       /* echo "<pre>";
        print_r($user_details);
        echo "<br />";
        exit;*/
        $offset = $data['offset'];
        $limit  = $data['limit'];
        /*######################### SERACH CONDITIONS #########################*/
        $where_raw = "";
        
        $where_raw .= "1=1";
        /************************ SERCH CONDITIONS END *************************/
       
        $order_by_raw = "`payment_histories`.`created_at` DESC";
            
        /*############################ SORTING PRODUCT ########################*/

        /*************************** SORTING PRODUCT END ***********************/

        /**/
        $advert_arr               = PaymentHistory::with('advert_details')->whereRaw($where_raw)->orderBy('id','DESC')->get();
        $product_info = PaymentHistory::join('adverts', 'adverts.id', '=', 'payment_histories.advert_id')
                        ->whereRaw($where_raw)
                        ->whereRaw('`payment_histories`.`site_user_id` = '.$user_details->id)
                        ->select('payment_histories.*','adverts.model_eng','adverts.model_arabic','adverts.is_active')
                        ->orderByRaw($order_by_raw)
                        ->skip($offset)
                        ->take($limit)
                        ->get();

        $no_of_product = PaymentHistory::join('adverts', 'adverts.id', '=', 'payment_histories.advert_id')
                        ->whereRaw($where_raw)
                        ->whereRaw('`payment_histories`.`site_user_id` = '.$user_details->id)
                        ->select('payment_histories.*','adverts.model_eng','adverts.model_arabic','adverts.is_active')
                        ->orderByRaw($order_by_raw)
                        ->count();
        //echo "<pre>";
       
        if(count($product_info)>0){
            $product_info = $product_info->toArray();
        }
        /*echo "<pre>";
        print_r($product_info);
        exit;*/
        //echo $no_of_product;
        //$no_of_product = count($product_info);
       
        //compact('product_info','no_of_product','offset','limit')
        return view('frontend.user.history_list',compact('product_info','no_of_product','offset','limit'));
    }


    /*******************************************/
    /***** Tranction History Function SOF *****/
    /*******************************************/
    public function getOrdertList(){

        $data = Request::all();
        $user_id = Session::get('user_id');
        $offset = $data['offset'];
        $limit  = $data['limit'];

        $order_info = OrderHistory::leftjoin('products', 'products.sku', '=', 'order_history.sku_number')
                                    ->select('products.image_url','products.name','order_history.*')
                                    ->where('user_id',$user_id)->orderBy('id','desc')->offset($offset)
                                    ->limit($limit)->get();

        $no_of_order = OrderHistory::where('user_id',$user_id)->get()->count();
        
        return view('frontend.home.orders',compact('order_info','no_of_order','offset','limit'));
    }

    public function getOrderHistory(){

        $this->authenticateUser();
        $user_id = Session::get('user_id');
        $title = 'OrderHistory';
        $total_no_of_orders = OrderHistory::where('user_id',$user_id)->get()->count();
        $total_no_of_withdraw = OrderHistory::where('user_id',$user_id)->get()->count();

        return view('frontend.home.user-profile-passbook-purchased',compact('title','total_no_of_orders','total_no_of_withdraw'));
    }


    public function getWithdrawlList(){

        $data = Request::all();
        $user_id = Session::get('user_id');
        $offset = $data['offset'];
        $limit  = $data['limit'];

        $withdrawals_info = WithdrawalHistory::where('user_id',$user_id)->orderBy('id','desc')->offset($offset)
                                    ->limit($limit)->get();

        $no_of_withdrawals = WithdrawalHistory::where('user_id',$user_id)->get()->count();
        
        return view('frontend.home.withdrawals',compact('withdrawals_info','no_of_withdrawals','offset','limit'));
    }
    /*******************************************/
    /***** Tranction History Function SOF *****/
    /*******************************************/

    /******************************VIEW USER TANGO GIFT CARD*************************************/

    public function getViewallgiftcard(){

        if (Session::has('user_id')){


        /****************Get user details ******************************/

        $userprofileheadinfo=Customhelpers::getUserDetails();
        $data=Request::all();
       
        $where="1";

        $id = Session::get('user_id');

        if(!empty($data["startdate"]) && !empty($data["enddate"])){
            $startdate=$data["startdate"];
            $enddate=$data["enddate"];
            if($startdate!='' && $enddate!=''){

            
           // $allgiftcarddetails=SiteUser::with(["tangoorder"=>function($query)use($startdate,$enddate){ $query->whereDate('created_at','>=',$startdate)->whereDate('created_at','<=',$enddate)->orderBy('id','desc'); }])->find($id);
           
               $allgiftcarddetails=Tangoorder::with("siteuser")->where('siteusers_id',$id)->where('is_deleted',0)->whereDate('created_at','>=',$startdate)->whereDate('created_at','<=',$enddate)->orderBy('id','desc')->paginate(5);
               
                $pagination = $allgiftcarddetails->appends ( array (
                'startdate' => Input::get ( 'startdate' ) ,
                'enddate'=>Input::get ( 'enddate' )
                ) );

            }
            else
            {
                 $allgiftcarddetails=Tangoorder::with("siteuser")->where('siteusers_id',$id)->where('is_deleted',0)->orderBy('id','desc')->paginate(5);
            }
        }
        else
        {

            //$allgiftcarddetails=SiteUser::with(["tangoorder"=>function($query){ $query->orderBy('id','desc'); }])->find($id);
            
            $allgiftcarddetails=Tangoorder::with("siteuser")->where('siteusers_id',$id)->where('is_deleted',0)->orderBy('id','desc')->paginate(5);
        }
                                
            $giftcount=$allgiftcarddetails->count();
            
            return view('frontend.tangoapi.viewallgiftcard',compact('allgiftcarddetails','giftcount','userprofileheadinfo'));

        }
        else{

            return redirect('login');
      }

        
        
    }

    public function getSellGiftCard(){

        if (Session::has('user_id')){

        /****************Get user details ******************************/

        $userprofileheadinfo=Customhelpers::getUserDetails();
         return view('frontend.tangoapi.sellgiftcard',compact('userprofileheadinfo'));
        }
        else{

            return redirect('login');
      }
    }

    /*********************************Soft delete user gift card***********************************/

    public function getGiftCardDelete($id){

        $tangoordercount=Tangoorder::find($id)->count();

        if($tangoordercount>0){

            $tangoorderdetails=Tangoorder::find($id);
            $tangoorderdetails->is_deleted=1;
            $tangoorderdetails->save();
            Session::flash('success_message', 'You have successfully deleted your gift card');
            return redirect('user/viewallgiftcard');
        }
        else{
            Session::flash('failure_message', 'Some error occurred.'); 
            return redirect('user/viewallgiftcard');
        }

    }

    /********************************************************************************
     *                               USER VIEW REFER DETAILS                                    *
     *******************************************************************************/
    
    function getViewReferDetails()
    {

        if (Session::has('user_id'))
    {

        $id = Session::get('user_id');

        $user_class             = "active";
        $module_head            = "View User Refer Details";
        $user_id                = $id;
		
		 
        /*$user_details           = SiteUser::with('userrefertolink')->where('id', '=', $id)->where('status','1')->where('is_deleted','0')->get();
        $refer=array();
        if(!empty($user_details)){
            foreach ($user_details[0]->userrefertolink as $key => $value) {
                # code...

                $referto=$value->referto;
                $userrefer=Userrefer::with('userreferlink1')->where('referto', '=', $referto)->where('status','1')->get();

                $count=$userrefer->count();
                if($count>0){

                $refercode=$value->refercode;
                $created_at=$value->created_at;
                $updated_at=$value->updated_at;

                $refer['userreferid'][$key]=$userrefer[0]->userreferlink1->id;

                $refer['firstname'][$key]=$userrefer[0]->userreferlink1->firstname;
                $refer['lastname'][$key]=$userrefer[0]->userreferlink1->lastname;
                $refer['email'][$key]=$userrefer[0]->userreferlink1->email;
                $refer['phoneno'][$key]=$userrefer[0]->userreferlink1->phoneno;

                $refer['refercode'][$key]=$refercode;
                $refer['created_at'][$key]=$created_at;
                $refer['updated_at'][$key]=$updated_at;

               }
            }
        }*/

        $user_details=Userrefer::with("userreferlink1")->where('referby',$id)->where('status',1)->where('usertype',0)->paginate(1);
		  
		
       /* if(!$user_details){
            Session::flash('failure_message', 'User not found.'); 
            Session::flash('alert-class', 'alert alert-danger'); 
            return redirect('admin/patient/list');
            exit;
        }*/
		
        $title  = "View User Refer Details";
        return view('frontend.userreferdetails.view_userrefer_details',compact(
            'module_head',
            'user_id',
            'user_class',
            'user_details',
            'title'
            ));

      }
      else{

        return redirect('login');
      }
    }

    public function getViewUserDetails($id){
       
        if (Session::has('user_id')){

            $id=base64_decode($id);
            $SiteUser   = SiteUser::find($id);
            $count=SiteUser::find($id)->count();
            return view('frontend.userreferdetails.view_siteuser_details',array('title'=>'View Site User','module_head'=>'View Site User','user_details'=>$SiteUser,'count'=>$count));

        }

        else{

        return redirect('login');
      }

        

    }

    /********************************************************************************
     *                              SHARE REFER CODE                                    *
     *******************************************************************************/
    public function shareReferCode(){

        if (Session::has('user_id')){

        $id = Session::get('user_id');
        $SiteUserReferId=SiteUserReferId::where("siteuser_id",$id)->where('status', '=', 1)->get();
        $SiteUser   = SiteUser::find($id);
        //$count=SiteUser::find($id)->count();


        $refercode='';
        if(!empty($SiteUserReferId)){
        foreach ($SiteUserReferId as $key => $value) {
        if($value->superaffiliate_status==1){
        $refercode=$value->referid;
        }
        else if($value->superaffiliate_status==0){
        $refercode=$value->referid;
        }
        else{
            $refercode='';
        }
        }
        }
        else
        {
        $refercode='';
        }
        $og_description="Referral code is ".$refercode; 
        $og_url=url()."/user/sharecode";
        $userprofileheadinfo=Customhelpers::getUserDetails();
        //$og_image='<img src="'.url('').'/public/frontend/images/fbshare.jpeg" style="height: 50px; width: 150px;">';
        return view('frontend.userreferdetails.sharecode',array('ogtitle'=>'Share refer code','module_head'=>'View Site User','user_details'=>$SiteUser,'SiteUserReferId'=>$SiteUserReferId,'og_description'=>$og_description,'og_url'=>$og_url,'userprofileheadinfo'=>$userprofileheadinfo));

        }

        else{

        return redirect('login');
      }

        
    }

    public function shareReferCodeInsert(){

        if (Session::has('user_id')){

             $data=Request::all();
        //echo "<pre>";
        //print_r($data);exit();
        $id = Session::get('user_id');
        $SiteUser   = SiteUser::find($id);
        if(!empty($SiteUser)){

            $referbyname=$SiteUser->firstname." ".$SiteUser->lastname;
        }
        else
        {
            $referbyname='';
        }
        
        $remembertoken=$data['remembertoken'];
        $fullname=$data["fullname"];
        $email=$data["email"];
        $refercode=$data["refercode"];
        $link=$data["link"];
        $id = Session::get('user_id');
        $datetime= Customhelpers::Returndatetime();
        Invitefriend::create(['remember_token'=>$remembertoken,'siteusers_id'=>$id,'name'=>$fullname,'email'=>$email,'created_at'=>$datetime,'refercode'=>$refercode]);



         ###################################Share Refer Code mail starts##############################
            
            $user_name  = $fullname;
            $user_email = $email;
            
            
            $admin_users_email = "";
            
            $sitesettings = DB::table('sitesettings')->where('name','email')->first();
            if(!empty($sitesettings))
            {
                $admin_users_email = $sitesettings->value;
            }
            
            if(!empty($refercode)){
                $subject = "Use this refer code to register in Cashback justin.";
            
                $message_body = $referbyname. " has sent you a refer code ".$refercode." . Use this refer code to register in Cashback justin.  <a href='".url('')."/signup'>Register link is</a>";
            }
            else if(!empty($link)){
                    $link=$link."&tk=".$remembertoken;
                    $subject = "Click on this link to register in Cashback justin.";
            
                    $message_body = $referbyname. " has sent you a link ".$refercode." . Use this link to register in Cashback justin. <a href='".$link."'>Click on this link to register</a>";

            }
            
            
            
            
            
            
            if(($_SERVER['REMOTE_ADDR'] != 'localhost') && ($_SERVER['REMOTE_ADDR'] != '192.168.1.115')) //check if code is ruuning in localhost
            {
                $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
                {
                    $message->from($admin_users_email,'Checkout Saver');
        
                    $message->to($user_email)->subject($subject);
                });
            }
            ################# Share Refer Code -end ################################





        Session::flash('success_message', 'You have successfully invited your friend');
        return redirect('user/sharecode');

        }

        else{

        return redirect('login');
      }

       
    }
	
	/********************************************************************************
     *                             INVITE FRIENDS LIST                                    *
     *******************************************************************************/

    public function viewInviteFriends(){

        if (Session::has('user_id')){

        $id = Session::get('user_id');
        $SiteUserDetails   = SiteUser::find($id);
        $SiteUserReferId = SiteUserReferId::where("siteuser_id",$id)->where('status', '=', 1)->first();
        $SiteUser=Invitefriend::with("siteuser")->where('siteusers_id',$id)->where('usertype',0)->get();  
        $count=$SiteUser->count();
        $pendinginvite = Invitefriend::where('siteusers_id',$id)->where('status',0)->get();
        $registerinvite = Userrefer::with("userreferlink1")->where('referby',$id)->where('usertype',0)->get();
        if(count($pendinginvite)>0){
            $pendinginvite = $pendinginvite->toArray();
        }
        if(count($registerinvite)>0){
            $registerinvite = $registerinvite->toArray();
        }

        /*echo '<pre>';
        print_r($pendinginvite);
        exit;*/

        $importfriendgoogleapikey=Sitesetting::where("name","importfriendgoogleapikey")->where("not_visible",0)->get();
        $importfriendgoogleapikey_count=Sitesetting::where("name","importfriendgoogleapikey")->where("not_visible",0)->count();

        if($importfriendgoogleapikey_count>0){

        $importfriendgoogleapikey=$importfriendgoogleapikey[0]->value;
        }
        else{
        $importfriendgoogleapikey="";
        }

        $importfriendgoogleclientid=Sitesetting::where("name","importfriendgoogleclientid")->where("not_visible",0)->get();
        $importfriendgoogleclientid_count=Sitesetting::where("name","importfriendgoogleclientid")->where("not_visible",0)->count();

        if($importfriendgoogleclientid_count>0){

        $importfriendgoogleclientid=$importfriendgoogleclientid[0]->value;
        }
        else{
        $importfriendgoogleclientid="";
        }

          $fbappiddata=Sitesetting::where("name","fbappid")->where("not_visible",'0')->get();
          $fbappidcount=Sitesetting::where("name","fbappid")->where("not_visible",'0')->count();
          if($fbappidcount>0){
            $fbappid=$fbappiddata[0]->value;
          }
          else{
            $fbappid="";
          }

        /****************Get user details ******************************/

        $userprofileheadinfo=Customhelpers::getUserDetails();

        return view('frontend.userreferdetails.invitefriends',array('title'=>'View Invite Friends','module_head'=>'View Invite Friends','user_details'=>$SiteUser,'count'=>$count,'SiteUserReferId'=>$SiteUserReferId,'profile_details' => $SiteUserDetails,'pending_invites'=>$pendinginvite,'registered_invites'=> $registerinvite,'userprofileheadinfo'=>$userprofileheadinfo,'importfriendgoogleapikey'=>$importfriendgoogleapikey,'importfriendgoogleclientid'=>$importfriendgoogleclientid,'fbappid'=>$fbappid));

       }
       else{

        return redirect('login');
      }

    }

    public function inviteFriend(){

        if (Session::has('user_id')){

            $data=Request::all();
            $id = Session::get('user_id');
            $existuser=Invitefriend::where('siteusers_id',$id)->where('email',$data["email"])->where('refercode',$data["refercode"])->get(); 
            $existuser=$existuser->count();
            if($existuser == 0){
            $SiteUser   = SiteUser::find($id);
            if(!empty($SiteUser)){
                $referbyname=$SiteUser->firstname." ".$SiteUser->lastname;
            }
            else
            {
                $referbyname='';
            }

            $remembertoken=rand().time();
            $fullname='';
            $email=$data["email"];
            $refercode=$data["refercode"];
            $link=$data["sharelink"];
            $id = Session::get('user_id');
            $datetime= Customhelpers::Returndatetime();
            $existinguser=Invitefriend::where('siteusers_id',$id)->where('email',$data["email"])->get(); 
            $existinguser=$existinguser->count();
            if($existinguser == 0)
                Invitefriend::create(['remember_token'=>$remembertoken,'siteusers_id'=>$id,'name'=>$fullname,'email'=>$email,'created_at'=>$datetime,'refercode'=>$refercode]);
            if($existinguser == 1)
                Invitefriend::where('siteusers_id',$id)->where('email',$data["email"])->update(['remember_token'=>$remembertoken,'refercode'=>$refercode]);
            ###################################Share Refer Code mail starts##############################
            
            $user_name  = $fullname;
            $user_email = $email;
            
            
            $admin_users_email = "";
            
            $sitesettings = DB::table('sitesettings')->where('name','email')->first();
            if(!empty($sitesettings))
            {
                $admin_users_email = $sitesettings->value;
            }
            
            if(!empty($refercode) && !empty($link)){
                $link=$link."?tk=".$remembertoken;
                $subject = "Use this refer code to register in Checkout Saver.";
            
                $message_body = $referbyname. " has sent you a refer code ".$refercode." . Use this refer code to register in Checkout Saver.  <a href='".$link."'>Register link is</a>";
            }
            /*else if(!empty($link)){
                    $link=$link."&tk=".$remembertoken;
                    $subject = "Click on this link to register in Cashback justin.";
            
                    $message_body = $referbyname. " has sent you a link ".$refercode." . Use this link to register in Cashback justin. <a href='".$link."'>Click on this link to register</a>";

            }*/
            
            
            
            
            
            
            if(($_SERVER['REMOTE_ADDR'] != 'localhost') && ($_SERVER['REMOTE_ADDR'] != '192.168.1.115')) //check if code is ruuning in localhost
            {
                $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
                {
                    $message->from($admin_users_email,'Checkout Saver');
        
                    $message->to($user_email)->subject($subject);
                });
            }
            ################# Share Refer Code -end ################################





        Session::flash('success_message', 'You have successfully invited your friend');
        return redirect('user/invitefriendslist');
        }
        else{
            Session::flash('failure_message', 'You have already invited this user.'); 
            Session::flash('alert-class', 'alert alert-danger'); 
            return redirect('user/invitefriendslist');
        }




        }
        else{
            return redirect('login');
        }
    }
    public function sendReferCode(){
        // echo 'called';
        $data=Request::all();
        $refercode=$data['refercode'];
        $referby = $data['id'];
        foreach ($data['email'] as $key => $value) {
            $useremail =  $value;
            $cmd = "wget -bq --spider ".url()."/refer-bulk/".$referby."/".$refercode."/".$useremail;
            shell_exec(escapeshellcmd($cmd));
            // echo escapeshellcmd($cmd);
        }
        // echo url();
        // print_r($data);
        /*$cmd = "wget -bq --spider ".url()."/hotel-owner/theme/copy-file-folder/".$customer_id;
                    shell_exec(escapeshellcmd($cmd));*/
        // exit;
    }

    public function sendInvite($referby,$refercode,$email){
        // echo $referby;
        $id = $referby;
        $data['refercode'] = $refercode;
        $data['email'] = $email;

        $existuser=Invitefriend::where('siteusers_id',$id)->where('email',$data["email"])->where('refercode',$data["refercode"])->get(); 
            echo $existuser=$existuser->count();
            if($existuser == 0){
            $SiteUser   = SiteUser::find($id);
            if(!empty($SiteUser)){
                $referbyname=$SiteUser->firstname." ".$SiteUser->lastname;
            }
            else
            {
                $referbyname='';
            }

            $remembertoken=rand().time();
            $fullname='';
            $email=$data["email"];
            $refercode=$data["refercode"];
            $link=$SiteUser->sharelink;
            $datetime= Customhelpers::Returndatetime();
            $existinguser=Invitefriend::where('siteusers_id',$id)->where('email',$data["email"])->get(); 
            $existinguser=$existinguser->count();
            if($existinguser == 0)
                Invitefriend::create(['remember_token'=>$remembertoken,'siteusers_id'=>$id,'name'=>$fullname,'email'=>$email,'created_at'=>$datetime,'refercode'=>$refercode]);
            if($existinguser == 1)
                Invitefriend::where('siteusers_id',$id)->where('email',$data["email"])->update(['remember_token'=>$remembertoken,'refercode'=>$refercode]);
            ###################################Share Refer Code mail starts##############################
            
            $user_name  = $fullname;
            $user_email = $email;
            
            
            $admin_users_email = "";
            
            $sitesettings = DB::table('sitesettings')->where('name','email')->first();
            if(!empty($sitesettings))
            {
                $admin_users_email = $sitesettings->value;
            }
            
            if(!empty($refercode) && !empty($link)){
                $link=$link."?tk=".$remembertoken;
                $subject = "Use this refer code to register in Cashback justin.";
            
                $message_body = $referbyname. " has sent you a refer code ".$refercode." . Use this refer code to register in Cashback justin.  <a href='".$link."'>Register link is</a>";
            }
            if(($_SERVER['REMOTE_ADDR'] != 'localhost') && ($_SERVER['REMOTE_ADDR'] != '192.168.1.115')) //check if code is ruuning in localhost
            {
                $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
                {
                    $message->from($admin_users_email,'Checkout Saver');
        
                    $message->to($user_email)->subject($subject);
                });
            }
    }
}
    /*****************************************************************************
    *                          UPDATE REFER CODE                                 *
    ******************************************************************************/

    public function updateReferCode(){
        $data = Request::all();
        $newcode = $data['newcode'];
        $oldcode = $data['oldcode'];
        $userId = $data['id'];
        $existcode = SiteUserReferId::where("refercode",$newcode)->count();
        if($existcode == 0){
            $SiteUserDetails   = SiteUser::find($userId);
            $new_link =  url().'/r/'.$newcode;
            $new_referid =  $newcode;
            $status = SiteUser::where('id',$userId)->update(['sharelink'=>$new_link]);
            $status = SiteUserReferId::where('siteuser_id', $userId)->update(['referid'=>$new_referid, 'refercode' => $newcode]);
            $session_flash_msg = 'Your Referal has been updated successfully.';
            if(App::getLocale()=='ar')
                $session_flash_msg = 'تم تحديث ملف التعريف الخاص بك بنجاح';
                
            Session::flash('success_message', $session_flash_msg);
            echo 1;

        }
        else{
            echo 0;
        }

    }


    /********************************************************************************
     *                            UNSUBSCRIBE USER                                   *
     *******************************************************************************/

    public function getUnsubscribeUser(){

        
        //$SiteUser=Invitefriend::with("siteuser")->where('siteusers_id',$id)->where('usertype',0)->paginate(1);  
        //$count=$SiteUser->count();

        

        return view('frontend.unsubscribe.index',array('title'=>'Unsubscribe user','module_head'=>'Unsubscribe user'));
        

    }


    public function postUnsubscribeUser(){

        

        //   $validator        = Validator::make($data, [
        //     'message'     => 'required',
            
        //  ]);

        // if ($validator->fails()) {
        //     return redirect()->route('userunsubscribe')->withErrors($validator)->withInput();
        // }   

        $data=Request::all();    
        $datetime= Customhelpers::Returndatetime();
        
        //$id = Session::get('user_id');
        //$SiteUser=Invitefriend::with("siteuser")->where('siteusers_id',$id)->where('usertype',0)->paginate(1);  
        //$count=$SiteUser->count();

        $msg=$data['message'];
        $encodedemail=$data['useremail'];
        $type=$data['type'];
        $email=base64_decode($encodedemail); 
        
        $siteuserdetails=SiteUser::where("email",$email)->where("is_deleted",0)->get();

        
        $id=$siteuserdetails[0]->id;


        $emailnotification=Emailnotification::where('slug',$type)->where('status',1)->get();
            
        $emailnotification_count=Emailnotification::where('slug',$type)->where('status',1)->count();

        if($emailnotification_count>0){
            $emailnotification_id=$emailnotification[0]->id;

            $emailnotification_Siteuser=Emailnotification_Siteuser::where('siteusers_id',$id)->where("emailnotifications_id",$emailnotification_id)->get();
            $emailnotification_Siteuser_count=Emailnotification_Siteuser::where('siteusers_id',$id)->where("emailnotifications_id",$emailnotification_id)->count();

                if($emailnotification_Siteuser_count>0){

                    //Checking the mail status on or off 1 means on 0 means off 

                    if($emailnotification_Siteuser[0]->status==1){

                        Emailnotification_Siteuser::where('siteusers_id',$id)->where("emailnotifications_id",$emailnotification_id)->update(['status'=>0]);

                        Unsubscribe::create(['siteusers_id'=>$id,'msg'=>$msg,'status'=>1,'created_at'=>$datetime,'updated_at'=>$datetime,'emailnotifications_id'=>$emailnotification_id]);
                        Session::flash('success_message', 'You have successfully unsubscribed.');
                        return redirect('user/unsubscribe?useremail='.$encodedemail.'&type='.$type);

                    }
                    else{

                        Session::flash('failure_message', 'You have already unsubscribed.'); 
                        Session::flash('alert-class', 'alert alert-danger'); 
                        return redirect('user/unsubscribe?useremail='.$encodedemail.'&type='.$type);
                    }

                        

                }

            
        }


        //$count=Unsubscribe::where('siteusers_id',$id)->count();

            // if($count==0){
            //     Unsubscribe::create(['siteusers_id'=>$id,'msg'=>$msg,'status'=>1,'created_at'=>$datetime,'updated_at'=>$datetime]);
            //     Session::flash('success_message', 'You have successfully unsubscribed.');
            //     return redirect('user/unsubscribe?useremail='.$encodedemail.'&type='.$type);
            // }
            // else{
            //     Session::flash('failure_message', 'You have already unsubscribed.'); 
            //     Session::flash('alert-class', 'alert alert-danger'); 
            //     return redirect('user/unsubscribe?useremail='.$encodedemail.'&type='.$type);
            // }

        
        
       

    }

    /*************************************Auto payment**************************************/

    public function getAutoPurchaseGiftCard()
    {

        if (Session::has('user_id'))
        {

            $id=Session::get('user_id');
            $SiteUser   = SiteUser::find($id);
            $count=SiteUser::find($id)->count();
            
            /****************Get user details ******************************/

            $giftcard=Giftcard::with("giftcarddetails")->where('displaystatus',1)->get();

            $Withdrawldetailscount=Withdrawldetails::where("withdrawoption",2)->where('siteusers_id',$id)->count();

            if($Withdrawldetailscount>0)
            {
                $Withdrawldetails=Withdrawldetails::where("withdrawoption",2)->where('siteusers_id',$id)->get();
                $giftcards_id=$Withdrawldetails[0]->giftcards_id;
                $giftcarddetails_id=$Withdrawldetails[0]->giftcarddetails_id;

                $giftcarddetail=Giftcarddetail::with("giftcard")->where("giftcard_id",$giftcards_id)->get();
            }
            else{
                 $giftcards_id=0;
                 $giftcarddetails_id=0;  
                 $giftcarddetail=array();
                 $Withdrawldetails=array();
            }

            $userprofileheadinfo=Customhelpers::getUserDetails();
            return view('frontend.usercashback.autopurchasegiftcard',array('title'=>'Auto  Purchase Gift Card','module_head'=>'Auto  Purchase Gift Card','SiteUser'=>$SiteUser,'count'=>$count,'userprofileheadinfo'=>$userprofileheadinfo,'giftcard'=>$giftcard,'Withdrawldetailscount'=>$Withdrawldetailscount,'giftcards_id'=>$giftcards_id,'giftcarddetails_id'=>$giftcarddetails_id,'giftcarddetail'=>$giftcarddetail,'Withdrawldetails'=>$Withdrawldetails));
        }

      else
      {

        return redirect('login');
      }
    }

    public function postAutoPurchaseGiftCardFacevalue(){

        if (Session::has('user_id'))
        {
            $data=Request::all();

            $giftcardid=$data["giftcardid"];

            $giftcarddetail=Giftcarddetail::with("giftcard")->where("giftcard_id",$giftcardid)->get();
            $price=array();
            
            foreach ($giftcarddetail as $key => $value) {
                
                $price["facevalue"][]=$value->facevalue;
                $price["giftcarddetailid"][]=$value->id;
                $price["currency"][]=$value->currencycode;
                $price["minvalue"][]=$value->min_value;
                $price["maxvalue"][]=$value->max_value;

            }

       
            echo json_encode($price);
        }

        else
        {

            return redirect('login');
        }

    }


    public function postAutoPurchaseGiftCard(){ 

        if (Session::has('user_id'))
        {
            $data=Request::all();
            // echo "<pre>";
            // print_r($data);
            // exit();
            if(!empty($data)){

                $userwithdrawoption=$data['userwithdrawoption'];
                //giftcard id
                $autogiftcardid=$data["autogiftcardid"];

                $autogiftcard_details=$data["autogiftcard_details_id"];
                $autogiftcard_detailsar=explode("-", $autogiftcard_details);

                //giftcard details id
                $autogiftcard_details_id=$autogiftcard_detailsar[0];

                //price

                $type=$autogiftcard_detailsar[3];

                if($type=='facevalue')
                {
                    $autogiftcard_details_price=$autogiftcard_detailsar[1];
                }
                else if($type=='minmaxrange')
                {
                    $autogiftcard_details_price=$data["yourvalue"];
                }

                
                

                $currency=$autogiftcard_detailsar[2];

                $id=Session::get('user_id');

                $transactionid=uniqid();

                $datetime= Customhelpers::Returndatetime();

                $withdrawoption=2;

                $Withdrawldetailscount=Withdrawldetails::where("withdrawoption",2)->where('siteusers_id',$id)->count();

                if($Withdrawldetailscount==0)
                {
                    Withdrawldetails::create(['siteusers_id'=>$id,'amount'=>$autogiftcard_details_price,'transactionid'=>$transactionid,'status'=>0,'created_at'=>$datetime,'updated_at'=>$datetime,'withdrawoption'=>2,'giftcards_id'=>$autogiftcardid,'giftcarddetails_id'=>$autogiftcard_details_id,'currency'=>$currency]);

                }
                else{

                    Withdrawldetails::where("withdrawoption",2)->where('siteusers_id',$id)->update(['siteusers_id'=>$id,'amount'=>$autogiftcard_details_price,'transactionid'=>$transactionid,'status'=>0,'updated_at'=>$datetime,'withdrawoption'=>2,'giftcards_id'=>$autogiftcardid,'giftcarddetails_id'=>$autogiftcard_details_id,'currency'=>$currency]);
                }

                SiteUser::where('id',$id)->update(['userwithdrawoption'=>$userwithdrawoption]);

                Session::flash('success_message', 'You have successfully saved.');
                return redirect('user/auto-purchase-giftcard');
            }
            else{
                 Session::flash('failure_message', 'Error occurred.'); 
                 Session::flash('alert-class', 'alert alert-danger');
                 return redirect('user/auto-purchase-giftcard');
            }

        }

        else
        {

            return redirect('login');
        }
        

    }

    /*******************Manual payment*******************************************/
    /*View User cashback balance  */

    public function getCashbackBalance(){


        if (Session::has('user_id')){

            $id=Session::get('user_id');
            $SiteUser   = SiteUser::find($id);
            $count=SiteUser::find($id)->count();
            /****************Get user details ******************************/

            $userprofileheadinfo=Customhelpers::getUserDetails();
            return view('frontend.usercashback.checkbalance',array('title'=>'View  User Cashback Balance','module_head'=>'View  User Cashback Balance','SiteUser'=>$SiteUser,'count'=>$count,'userprofileheadinfo'=>$userprofileheadinfo));

        }

        else{

        return redirect('login');
      }

    }

    /* Withdraw user amount */

    public function postWithdrawCashbackAmount(){

        if (Session::has('user_id')){

         $id=Session::get('user_id');
         $SiteUser   = SiteUser::find($id); 
         //echo "<pre>";
         //print_r($SiteUser);exit();
         $paypalid=$SiteUser->paypalid;
         $walletbalance=$SiteUser->wallettotalamount;
         $datetime= Customhelpers::Returndatetime();
         $data=Request::all();
       
        $useramount=$data['amount'];

        $totalwithdrawlamount=Withdrawldetails::where("siteusers_id",$id)->whereIn('status', [0,1])->sum('amount');

        if($totalwithdrawlamount==''){
            $totalwithdrawlamount=0;
        }
        
              if($useramount==''){

                    Session::flash('failure_message', 'Please give the right amount.'); 
                    Session::flash('alert-class', 'alert alert-danger'); 
                    return redirect('user/cashbackbalance');
                }

                $regex="/(\d+(\.\d+)?)/";

                if (!preg_match($regex, $useramount)) {
                    Session::flash('failure_message', 'Please give the right amount.Amount must be integer or double.'); 
                    Session::flash('alert-class', 'alert alert-danger'); 
                    return redirect('user/cashbackbalance');
                } 

                if($paypalid==''){
                    
                    Session::flash('failure_message', "Please set your paypal id."); 
                    Session::flash('alert-class', 'alert alert-danger'); 
                    return redirect('user/cashbackbalance');

                }

                if($walletbalance<10){


                    Session::flash('failure_message', "Your minimum account balance must be $10."); 
                    Session::flash('alert-class', 'alert alert-danger'); 
                    return redirect('user/cashbackbalance');
                }

                if($useramount>100){

                     Session::flash('failure_message', "Your can withdraw maximum $100 at a time."); 
                    Session::flash('alert-class', 'alert alert-danger'); 
                    return redirect('user/cashbackbalance'); 
                }
                if($useramount>($walletbalance-$totalwithdrawlamount)){

                    Session::flash('failure_message', "You donot have sufficient fund."); 
                    Session::flash('alert-class', 'alert alert-danger'); 
                    return redirect('user/cashbackbalance'); 
                }
                else{


                    Withdrawldetails::create(['siteusers_id'=>$id,'amount'=>$useramount,'transactionid'=>uniqid(),'status'=>0,'created_at'=>$datetime,'updated_at'=>$datetime]);

                    //check email notification in profile is on or off

                    $emailnotification=Emailnotification::where('slug','Cashback-Withdrawal-everytime')->where('status',1)->get();

                    $emailnotification_count=Emailnotification::where('slug','Cashback-Withdrawal-everytime')->where('status',1)->count();
                    if($emailnotification_count>0){
                    $emailnotification_id=$emailnotification[0]->id;


                    $emailnotification_Siteuser=Emailnotification_Siteuser::where('siteusers_id',$id)->where("emailnotifications_id",$emailnotification_id)->get();


                    $emailnotification_Siteuser_count=Emailnotification_Siteuser::where('siteusers_id',$id)->where("emailnotifications_id",$emailnotification_id)->count();

                    if($emailnotification_Siteuser_count>0){

                    $mailstatus=$emailnotification_Siteuser[0]->status;

                            if($mailstatus==1)
                            {

                                      /**************************SEND MAIL -start *****************************/
                                    
                                    $siteuserdata=SiteUser::find($id);   
                                    $site = Sitesetting::where(['name' => 'email'])->first();
                                    $admin_users_email = $site->value;

                                    
                                    $user_name = $siteuserdata->firstname.' '.$siteuserdata->lastname;
                                    $user_email = $siteuserdata->email;
                                    

                                    $subject = "Your withdrawl request in Checkout Saver";
                                    $message_body = " You have requested to withdraw money in Checkout Saver.Your withdrawl amount is "." $".number_format($useramount,2);


                                    $mail = Mail::send(['html' => 'frontend.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
                                    {
                                    $message->from($admin_users_email,'Checkout Saver');

                                    $message->to($user_email)->subject($subject);
                                    });

                                    /**************************SEND MAIL -end *****************************/ 
                            }
                        }
                    }








                    Session::flash('success_message', 'The withdraw request might take some time to be processed by admin.Your withdrawl request has been sent to admin.');
                    return redirect('user/cashbackbalance');
                }

         }

        else{

        return redirect('login');
      }

    }

    /* Credits and debits */

    public function viewCreditsDebits(){

        if (Session::has('user_id')){
             /****************Get user details ******************************/

            $userprofileheadinfo=Customhelpers::getUserDetails();
            $id=Session::get('user_id');
            $walletdetailscount=Walletdetails::with('siteusers')->whereIn('purpose_state',['0','1','2','3','5'])->where('siteusers_id',$id)->orderBy('id','desc')->count();
            $walletdetails=Walletdetails::with('siteusers')->whereIn('purpose_state',['0','1','2','3','5'])->where('siteusers_id',$id)->orderBy('id','desc')->paginate(10);
            return view('frontend.usercreditdebit.creditdebitdetails',array('title'=>'View  User Credit Debit Details','module_head'=>'View  User Credit Debit Details','walletdetails'=>$walletdetails,'userprofileheadinfo'=>$userprofileheadinfo,'walletdetailscount'=>$walletdetailscount));
            

        }
        else{

        return redirect('login');
      }

    }

    public function shareLinkinFb(){

        $data=Request::all();
        $link=url().'/user/sharelinkinfb?code='.$data['code'];
        $code = $data['code'];
        return view('frontend.userreferdetails.fbshare',array('link'=>$link,'code'=>$code));
    }

}