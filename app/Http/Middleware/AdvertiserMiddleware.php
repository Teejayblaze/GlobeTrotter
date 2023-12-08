<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use \App\Http\Traits\UserProfileTrait;

class AdvertiserMiddleware
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
        // $user = $this->get_user_profile();
        // if ($user)
        if(\session()->has('operator')) {
            return redirect()->route('operator_dashboard')->with('session-mismatch', 'You logged in at the wrong arena but we have redirected you to the right arena');
        }
        if (Auth::check() && Auth::user()->user_type_id === 1) {
            return redirect()->route('CorporateDashboard');
        } 
        else if (Auth::check() && Auth::user()->user_type_id === 2) {
            return redirect()->route('IndividualDashboard');
        }
        Auth::logout();
        return redirect()->route('advertiser_login');
    }
}
