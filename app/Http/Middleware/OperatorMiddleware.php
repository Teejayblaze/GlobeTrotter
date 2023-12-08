<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use \App\Http\Traits\UserProfileTrait;

class OperatorMiddleware
{
    use UserProfileTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\session()->has('individual')) {
            return redirect()->route('IndividualDashboard')->with('session-mismatch', 'You logged in at the wrong arena but we have redirected you to the right arena');
        }
        $user = $this->get_user_profile('operator');
        if ($user) {
            if ( $user->operator === 1 ) {
                $request->attributes->add(['user' => $user]);
                return $next($request);
            } 
        }
        
        Auth::logout();
        return redirect()->route('operator_login');
    }
}
