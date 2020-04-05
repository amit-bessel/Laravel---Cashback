<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Routing\Middleware;

class Language implements Middleware {

    public function __construct(Application $app, Redirector $redirector, Request $request) {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
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
		
		 // Make sure the current local exists
        $locale = $request->segment(1);

        // If the locale is added to to skip_locales array continue without locale
        if (in_array($locale, $this->app->config->get('app.skip_locales'))) {
            return $next($request);
        } else {
            // If the locale does not exist in the locales array continue with the fallback_locale
            if (! array_key_exists($locale, $this->app->config->get('app.locales'))) {
                $segments = $request->segments();

                if(isset($_SESSION['langpp']))
                    $this->app->setLocale($_SESSION['langpp']); 
                else{
                    $this->app->setLocale($this->app->config->get('app.fallback_locale')); 
                    $_SESSION['langpp'] = $this->app->config->get('app.fallback_locale');
                }
                //echo $_SESSION['langpp'];

                array_unshift($segments, $_SESSION['langpp']);
                 //$segments[0] = $_SESSION['langpp'];
                return $this->redirector->to(implode('/', $segments));
            }
        }
        $this->app->setLocale($locale);

        return $next($request);
		
        
    }

}