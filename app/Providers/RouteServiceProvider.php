<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    /*public function map(Router $router, Request $request)
    {
        $locale = $request->segment(1);
        session_start();

        if($locale=='ar' || $locale=='en'){
            $this->app->setLocale($locale);
            $_SESSION['langpp'] = $locale;
        }
        else{
            if(isset($_SESSION['langpp']))
                $this->app->setLocale($_SESSION['langpp']); 
            else
            {
                $this->app->setLocale($this->app->config->get('app.fallback_locale')); 
                $_SESSION['langpp'] = $this->app->config->get('app.fallback_locale');
            }
        }
        

        $skip_locales= $this->app->config->get('app.skip_locales');  
    
        // If the locale is added to to skip_locales array continue without locale
        if (in_array($locale, $skip_locales)) {
            $router->group(['namespace' => $this->namespace], function($router)
            {
                require app_path('Http/routes.php');
            });
        }
        else {
            $router->group(['namespace' => $this->namespace, 'prefix' => $locale], function($router) {
                require app_path('Http/routes.php');
            });
        }
        
    }*/
    
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
