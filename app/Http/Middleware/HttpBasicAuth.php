<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Model\User;
use App\Model\Sitesetting;
use Auth;
use Illuminate\Support\Facades\Request;

class HttpBasicAuth
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $username = Sitesetting::where('name','=','http_username')->pluck('value');
        $password = Sitesetting::where('name','=','http_password')->pluck('value');
        //$headers = apache_request_headers();
        $headers = Request::all();

        //print_r($headers);echo ' hello';exit;
        //if($request->getUser() != $username && $request->getPassword() != $password) {
        if($headers['username'] == $username && $headers['password'] == $password) {
            return $next($request);
        }else{
            $headers = array('WWW-Authenticate' => 'Basic');
            return response('Unauthorized', 401, $headers);
        }
        
     
    }
}
