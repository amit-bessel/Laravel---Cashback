<?php

namespace App\Http\Controllers\Auth;

use App\Model\User;
use App\Model\Sitesetting;
use Validator;
use App\Http\Controllers\Controller;
use Cookie;
//use Request;
//use App\Http\Requests;

use Illuminate\Http\Request;

//use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Session;


class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
	
	use AuthenticatesUsers;

    protected $redirectAfterLogout = 'auth/login';
    protected $redirectTo = '/admin/home';


    //use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
        if (!empty($_REQUEST['timezone'])) {
            Session::put('timezone', $_REQUEST['timezone']);
        }
        
        $this->middleware('guest', ['except' => 'getLogout']);
    }
	
	public function authenticate()
    {
        if (Auth::attempt(['email' => $email, 'password' => $password, 'status' => 1])) {
            // Authentication passed...

            return redirect()->intended('/admin/home');
        }
    }

     //public function postLogin(Request $request) {
     //   echo "<pre>"; print_r($request->input('login'));
     //    echo 1;exit;
     //    $field = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
     //    $request->merge([$field => $request->input('login')]);
     //    $this->username = $field;
     //
     //    return self::laravelPostLogin($request);
     //}
    // public function getLogout(){
    //     //echo "logout";
    // }







    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

/*******************getLogin function overwrites the parent class AuthenticatesUsers method get login************************/

public function getLogin()
{
    
        /**********Get google recaptcha code from site settings***************/ 
        
        $data["googlerecaptchainfo"]=Sitesetting::where("name","admingooglerecaptcha")->where('not_visible','0')->get();
        if (view()->exists('auth.authenticate')) {
            return view('auth.authenticate');
        }

        return view('auth.login',compact('data'));
}



/*******************PostLogin function overwrites the parent class AuthenticatesUsers method post login************************/

public function postLogin(Request $request)
 {
 
 $this->validate($request, [
 $this->loginUsername() => 'required', 'password' => 'required','g-recaptcha-response' => 'required'
 ]);

 // If the class is using the ThrottlesLogins trait, we can automatically throttle
 // the login attempts for this application. We'll key this by the username and
 // the IP address of the client making these requests into this application.
 $throttles = $this->isUsingThrottlesLoginsTrait();

 if ($throttles && $this->hasTooManyLoginAttempts($request)) {
 return $this->sendLockoutResponse($request);
 }

 $credentials = $this->getCredentials($request);
 $email = $request->input('email');
 $pass = $request->input('password');

 $user=User::where("email",$email)->get();
 $usercount=User::where("email",$email)->count();
 
 if($usercount>0){

        //if user is inactive
        if($user[0]->status==0){

            Session::flash('failure_message', 'You are inactive user.Please contact with admin.'); 
            Session::flash('alert-class', 'alert alert-danger'); 
            return redirect('/admin');

        }
        //if user is deleted
        else if($user[0]->is_deleted==1){

            Session::flash('failure_message', 'You have been deleted.Please contact with admin.'); 
            Session::flash('alert-class', 'alert alert-danger'); 
            return redirect('/admin');

        }
   }
    

 Session::put('userlogedemail', $email);
 Session::put('userlogedpass', $pass);
 
 if (Auth::attempt($credentials, $request->has('remember'))) {
 if($request->has('remember_me') == 1){
 Cookie::queue(Cookie::make('admin_email', $credentials['email'], 60*24*30));
 Cookie::queue(Cookie::make('admin_pass', $credentials['password'], 60*24*30));
 }else{
 Cookie::queue(Cookie::forget('admin_email'));
 Cookie::queue(Cookie::forget('admin_pass'));

 }
 return $this->handleUserWasAuthenticated($request, $throttles);
 }

 // If the login attempt was unsuccessful we will increment the number of attempts
 // to login and redirect the user back to the login form. Of course, when this
 // user surpasses their maximum number of attempts they will get locked out.
 if ($throttles) {
 $this->incrementLoginAttempts($request);
 }

 return redirect($this->loginPath())
 ->withInput($request->only($this->loginUsername(), 'remember'))
 ->withErrors([
 $this->loginUsername() => $this->getFailedLoginMessage(),
 ]);
 }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
	
	
	
	
}
