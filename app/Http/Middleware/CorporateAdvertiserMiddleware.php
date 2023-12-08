<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use \App\Http\Traits\UserProfileTrait;

class CorporateAdvertiserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    use UserProfileTrait;
    public function handle($request, Closure $next)
    {
        if(\session()->has('operator')) {
            return redirect()->route('operator_dashboard')->with('session-mismatch', 'You logged in at the wrong arena but we have redirected you to the right arena');
        }
        $user = $this->get_user_profile('corporate');
        if ($user) {
            if ($user->user_type_id === 1) {
                $request->attributes->add(['user' => $user]);
                return $next($request);
            }
        }
        return redirect()->route('advertiser_login'); 
    }
}
