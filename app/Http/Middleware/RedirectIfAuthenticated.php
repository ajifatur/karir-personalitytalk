<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
		$primaryKey = (new \App\Models\User())->getKeyName();
		Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/activities.log'),
        ])->info(
            json_encode([
                'user_id' => Auth::guard($guard)->check() ? $request->user()->{$primaryKey} : null,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip()
            ])
        );
		
        if (Auth::guard($guard)->check() && ($request->user()->role == role('admin') || $request->user()->role == role('hrd')) && $request->path() == 'login') {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
