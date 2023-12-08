<?php

namespace App\Http\Middleware;

use Closure;

class AssetBookingMiddleWare
{
    use \App\Http\Traits\UserProfileTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $this->get_user_profile('individual');
        $is_user_loggedin = false;

        if ($user) $is_user_loggedin = true;
        else {
            $user = $this->get_user_profile('corporate');
            if ($user) $is_user_loggedin = true;
            else {
                $user = $this->get_user_profile('operator');
                if ($user)  $is_user_loggedin = true;
            }
        }
        
        if ($user) $request->attributes->add(['user' => $user]);
        return $next($request);
    }
}
