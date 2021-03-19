<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check() && $request->user()->role == 1 && $request->path() == 'login') {
            return redirect('/admin');
            // return redirect()->intended('/');
        }
        elseif (Auth::guard($guard)->check() && $request->user()->role == 2 && $request->path() == 'login') {
            return redirect('/hrd');
            // return redirect()->intended('/');
        }

        return $next($request);
    }
}
