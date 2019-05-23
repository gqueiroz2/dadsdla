<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {     
        /*if($_SERVER['SERVER_ADDR'] == '10.36.12.151'){
           \URL::forceScheme('https');
        }*/
       
    }
}
