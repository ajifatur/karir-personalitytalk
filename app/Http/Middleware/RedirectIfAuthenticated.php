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
        if (Auth::guard($guard)->check() && ($request->user()->role == role_admin() || $request->user()->role == role_hrd()) && $request->path() == 'login') {
            return redirect('/admin');
            // return redirect()->intended('/');
        }

        return $next($request);
    }
}
