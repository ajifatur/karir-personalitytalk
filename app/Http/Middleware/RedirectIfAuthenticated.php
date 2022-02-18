<?php

namespace App\Http\Middleware;

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
        // if (Auth::guard($guard)->check() && ($request->user()->role_id == role('admin') || $request->user()->role_id == role('hrd')) && $request->path() == 'login') {
        if (Auth::guard($guard)->check() && $request->user()->role->is_admin == 1 && $request->path() == 'login') {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
