<?php

namespace App\Http\Middleware;

use Closure;

class AdminUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if (\session()->has('user')) {
            $request->attributes->add(['user' => \session()->get('user')]);
            return $next($request);
        }
        
        \session()->flush();
        return redirect()->route('admin_login');
    }
}
