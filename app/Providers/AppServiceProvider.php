<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use App\Models\Tes;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if(File::exists(base_path('vendor/ajifatur/faturhelper/src'))){
            foreach(glob(base_path('vendor/ajifatur/faturhelper/src').'/HelpersExt/*.php') as $filename){
                require_once $filename;
            }
        }        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function($view){
            if(Auth::check()){
                if(Auth::user()->role == 1 || Auth::user()->role == 2){
                    // Get tes
                    $tes = Tes::all();

                    // Send variable
                    view()->share('global_tes', $tes);
                }
            }
        });
    }
}
