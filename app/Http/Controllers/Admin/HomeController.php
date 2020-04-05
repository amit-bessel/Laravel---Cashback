<?php namespace App\Http\Controllers\Admin;

use App\Book;
use App\User;
use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Helper\helpers;
use App\Model\Country;  /* Model name*/
use App\Model\SiteUser;  /* Model name*/
use App\Model\Userrefer;  /* Model name*/
use App\Model\Invitefriend;  /* Model name*/
use App\Model\Walletdetails;  /* Model name*/
use App\Model\Withdrawldetails;  /* Model name*/
use Input; /* For input */
use Validator;
use Session;
use DB;
use Mail;
use Hash;
use Auth;
use App\Model\Previousemail; /* Model name*/
use Customhelpers;

class HomeController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
  public function __construct() {
      parent::__construct();
      view()->share('home_class','active');
      if(Auth::user()){
        $admin_role = Auth::user()->role;
        view()->share('user_role',$admin_role);
      }
    }
  public function index()
  {
    //===total user====
    $totel_user =SiteUser::where("is_deleted",0)->orderBy('id','DESC')->get();

    //===total active user====
    $totel_active_user =SiteUser::orderBy('id','DESC')->where('status', '=', 1)->where("is_deleted",0)->get();
    $totel_advert =0;
    $module_head = "Checkout Saver";

    //=====refered registerations====
    $userreferdetails=Userrefer::select('referto')->where("status",1)->get();
    foreach ($userreferdetails as $key => $value) {
      $userreferid[]=$value->referto;
    }

    //====independent regsitrations=====

    $independentsiteuserdetails=SiteUser::select("id")->where("is_deleted",0)->whereNotIn('id',$userreferid)->get();
   
    //=====total email invite users======

    $totalinvitefrienddetails=Invitefriend::where("id",'>','0')->get();

    //======total user wallet money======

    $walletsumar=SiteUser::selectRaw("sum(wallettotalamount) as sumwalletmoney")->get();
    $walletsum=$walletsumar[0]->sumwalletmoney;


    //=====total transaction =======

    $transactiondetails=Walletdetails::where("id",'>','0')->get();

    //====total withdraw pending=====

    $withdrawdetailspending=Withdrawldetails::where("id",'>','0')->where('status','0')->get();

    // echo "<pre>";
    // print_r($walletsum);exit();
      
    // =======total cashback processing===========
      
      $cashbackprocessing=Walletdetails::where("walletstatus",'0')->where("purpose_state",3)->get();

    // total super affiliate commission   

      $totalsuperaffiliatepayout=Walletdetails::selectRaw("sum(total) as totalsuperaffiliatecommission")->where("purpose_state",'5')->get();
      

   return view('admin.home.index',compact('totel_user','totel_active_user','totel_advert','module_head','userreferdetails','independentsiteuserdetails','totalinvitefrienddetails','walletsum','transactiondetails','withdrawdetailspending','cashbackprocessing','totalsuperaffiliatepayout'));
  }

  // For Admin Edit Profile
  public function getProfile()
  {
    
    $user=User::find(Auth::id());
      // return view('admin.books.edit',compact('book'));
    //return view('admin.home.edit_profile',compact('user'));
    
    return view('admin.home.edit_profile',array('title' => 'Edit Profile','module_head'=>'Edit Profile','user'=>$user));
  }
  
  public function show($id)
  {
     $user=User::find(Auth::id());
    return view('admin.home.edit_profile',compact('user'));
  }


  

  public function update(Request $request, $id)
  {
  
    $userUpdate=Request::all();


    $user=User::find($id); 

    if (Input::hasFile('image'))
    {
      $destinationPath = 'uploads/admin_profile/'; // upload path
      $thumb_path = 'uploads/admin_profile/thumb/';
      $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
      $fileName = rand(111111111,999999999).'.'.$extension; // renameing image
      Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
      // $this->create_thumbnail($thumb_path,$fileName,$extension); 
      $user['admin_icon']=$fileName;

      // unlink old photo
      @unlink('uploads/admin_profile/'.Request::input('admin_icon'));
    }
    else
       $user['admin_icon']=Request::input('admin_icon');


     User::where("id",$id)->update(['firstname'=>$userUpdate['firstname'],'lastname'=>$userUpdate['lastname'],'email'=>$userUpdate['email']]);

    $datetime= Customhelpers::Returndatetime();
    
    $count=Previousemail::where('email',$userUpdate['email'])->count();

    if($count==0){

        Previousemail::create(['email'=>$userUpdate['email'],'created_at'=>$datetime]);

    }
     
     Session::flash('success', 'Your profile updated successfully.'); 
     return redirect('admin/admin-profile');
  }

  public function forgotPassword()
    {
        if (Auth::check())
        {
            return redirect('admin/home');
        }
        else{
            return view('admin.home.forgotpassword');
        }
      
    }

  public function forgotpasswordcheck()
    {
        if(Request::isMethod('post'))
        {
          $email = Request::input('email');

          $users = USER::where('email',$email)->get();

          //echo $users->count();exit();
          if($users->count() == 0){
            Session::flash('failure_message', 'Invalid email id!'); 
            Session::flash('alert-class', 'alert alert-danger'); 
            return redirect('admin/forgotpassword/');
          }else{
            $random_code = mt_rand();
            User::where('email', '=', $email)->update(['code_number' => $random_code]);

            $sitesettings = DB::table('sitesettings')->get();
            //exit;
            if(!empty($sitesettings))
            {
              foreach($sitesettings as $each_sitesetting)
              {
                if($each_sitesetting->name == 'email')
                {
                  $admin_users_email = $each_sitesetting->value;
                }
              }
            }


            ###################################Sening mail starts##############################
            $firstname=User::where('email',$email)->first()->firstname;
            $lastname=User::where('email',$email)->first()->lastname;
            $user_name = $firstname." ".$lastname;
            $user_email = User::where('email',$email)->first()->email;

            //$resetpassword_link = url().'/admin/resetpassword/'.base64_encode($user_email).'-'.base64_encode($random_code);
            //$message_body ="Please click on the bellow link and reset your password.<br/>".$resetpassword_link;

            $message_body = "<a href='".url()."/admin/resetpassword/".base64_encode($user_email).'-'.base64_encode($random_code)."'>Click Here to reset your password.</a>";

            $subject = "Forgot Password Email";

            // $sent = Mail::send('admin.emailtemplate.send_mail', array('name'=>$user_name,'email'=>$user_email,'message_body'=>$resetpassword_link), 
            // function($message) use ($admin_users_email, $user_email,$user_name)
            // {
            //     $message->from($admin_users_email,'Checkout Saver');
            //     $message->to($user_email, $user_name)->subject('Forgot Password Email!');
            // });


            $sent = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
            {
            $message->from($admin_users_email,'Checkout Saver');

            $message->to($user_email)->subject($subject);
            });

            if( ! $sent) 
            {
              Session::flash('error', 'something went wrong!! Mail not sent.'); 
              return redirect('admin/forgotpassword');
            }
            else
            {
              Session::flash('success', 'Please check your email to reset your password.'); 
              return redirect('auth/login');
            }  
            ###################################Sening mail ends##############################
          }
          
        }
        
    }
  
   public function resetpassword($email)
    {
      //$user_email = base64_decode($email);
        if (Auth::check())
        {
            return redirect('admin/home');
        }
        else{
            
            $user_email = explode('-',$email);
      
            //$user_email = base64_decode($user_email[0]);
            //echo "h= ".$user_email; exit;
            $usr_email = base64_decode($user_email[0]);
            $users = USER::where('email',$usr_email)->get();
            if($users->count() == 0){
              Session::flash('failure_message', 'Invalid Link. Please Try again.'); 
              return redirect('admin/forgotpassword');
            }
            else{
              compact('users');
              if($users[0]->code_number==''){
                    Session::flash('failure_message', 'Invalid Link. Please Try again.'); 
                    return redirect('admin/forgotpassword');
              }
              else{
                return view('admin.home.resetpassword',array('title'=>'Reset Password','admin_email'=>$user_email[0]));
              }
            }
        }
      
    }
    
    public function usedResetPassword(){}
 public function updatePassword($email)
    {
      $user_email = base64_decode($email);
      $password = Request::input('password');
      $password = Request::input('con_password');
      //echo "h= ".$user_email; exit;Hash::make()
      DB::table('users')
            ->where('email', $user_email)
            ->update(['password' => Hash::make($password),'code_number'=>'']);

      Session::flash('success', 'Password successfully changed.'); 
      //return view('admin.home.resetpassword');
      //return redirect('admin/resetpassword/'.$email);
      //Session::flash('success', 'Please check your email to reset your password.'); 
      return redirect('auth/login');
    }

  public function changePass()
  {
    if(Request::isMethod('post'))
    {
      $old_password = Request::input('old_password');
      

      $password = Request::input('password');
      $conf_pass = Request::input('conf_pass');

      // Get Admin's password
      $user=User::find(Auth::id());

      if(Hash::check($old_password, $user['password']))
      {
        if($password!=$conf_pass){
          Session::flash('error', 'Password and confirm password is not matched.'); 
          return redirect('admin/change-password');

        }
        else{
          DB::table('users')->where('id', Auth::id())->update(array('password' => Hash::make($password)));

          Auth::logout(); //logout after successfully change password
          
          Session::flash('success', 'Password successfully changed.You can now relogin.'); 
          //return redirect('admin/change-password');
          return redirect('/admin');
        }
      }
      else{
        Session::flash('error', 'Old Password does not match.'); 
        return redirect('admin/change-password');
      }
    }

    return view('admin.home.changepassword',array('title' => 'Change Password','module_head'=>'Change Password'));
  }
  
	public function multiple_record_operations()
	{
		$data = Request::all();
		/*echo "<pre>";
		print_r($data);
		exit;*/
		$record_ids = base64_decode($data['record_ids']);
		$return_url = base64_decode($data['return_url']);
		$table_name	= base64_decode($data['table_name']);
		if($table_name=='adverts'){
      $update_fields = array(
                        'is_active' => $data['status']
                      );
    }
    else if($table_name=='walletdetails'){
      $datetime= Customhelpers::Returndatetime();
      $update_fields = array(
                        'walletstatus' => $data['status'],
                        'updated_at'=>$datetime
                      );
    }
    else{
      $update_fields = array(
                        'status' => $data['status']
                      );
    }
		DB::table($table_name)->whereRaw("`id` IN (".$record_ids.")")
			->update($update_fields);
      
		Session::flash('success_message', 'Status updated successfully.'); 
		return redirect($return_url);
	}

}
