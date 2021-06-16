<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes(){

        Route::group([
                        'middleware' => 'web',
                        'namespace' => $this->namespace,
                     ],function($router){
                            require base_path('routes/web.php');
                            require base_path('routes/results.php');
                            require base_path('routes/dataManagement.php');
                            require base_path('routes/auth.php');
                            require base_path('routes/performance.php');
                            require base_path('routes/pAndR.php');
                            require base_path('routes/ajax.php');
                            require base_path('routes/checkElements.php');
                            require base_path('routes/insert.php');
                            require base_path('routes/excel.php');
                            require base_path('routes/ranking.php');
                            require base_path('routes/dashboards.php');
                            require base_path('routes/relationShip.php');
                            require base_path('routes/viewer.php');
                            require base_path('routes/analytics.php');
                            require base_path('routes/planning.php');
                            require base_path('routes/salesManagement.php');
                            require base_path('routes/forecast.php');
                            require base_path('routes/test.php');
                     });
/*
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));*/
    }    

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
