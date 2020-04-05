<?php
namespace App\Http\Middleware;
use Closure;
use App\Model\SiteUser; /* Model name*/
use Session;
use App\Http\Requests;
use Redirect;
class LoginMiddleware {
   public function handle($request, Closure $next) {
	  // echo "Mylogin";
	   if (Session::has('user_id')){
		   $SiteUser=SiteUser::find(Session::get('user_id'));
		   // is_login status is used for if logout for one browser auto logout from other browser
		   if($SiteUser->is_login==0){
			   session::flush();
			   return redirect('/login');
		   }
	   }
      return $next($request);
   }
}