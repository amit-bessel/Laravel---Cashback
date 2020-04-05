<?php

namespace App\Http\Middleware;
use Closure;
use App\Model\SiteUser; /* Model name*/
use Session;
use App\Http\Requests;
use Redirect;
use Auth;
use App\Model\Module;
use App\Model\User;
use App\Model\Usersmodules;

class SubadminPermissionMiddleware {
   public function handle($request, Closure $next,$modulename) {

   	
	  $userId = Auth::id();
	  $user=User::find($userId);
	  $role=$user->role;

	  if(!empty($userId)){

	  		if($role==2){
				
	  			$modulear=Module::where("slug",$modulename)->get();
	  			$moduleid=$modulear[0]->id;
	  			$count=Usersmodules::where("users_id",$userId)->where("modules_id",$moduleid)->count();
	  			if($count==0){
	  				Session::flash('failure_message', 'You are not authorised to access this module !'); 
	  				return redirect('/admin/home');
	  			}
	  		}
	  }

	  
      return $next($request);
   }
}