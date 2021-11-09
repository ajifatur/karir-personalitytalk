<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
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
        //
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
