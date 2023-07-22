<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Illuminate\Support\Facades\View;

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
        $this->app->bind('.htaccess', function(){
            return base_path().'/../webroot/ROOT';
        });
        View::share(([]));
    }
}
