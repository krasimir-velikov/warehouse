<?php

namespace App\Http\Middleware;

use Closure;

class CheckBlocked
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
        if (auth()->check() && auth()->user()->status==0) {
            auth()->logout();

            return redirect()->route('login')->withMessage('Your account has been blocked. Please contact your administrator.');
        }
        elseif(auth()->check() && auth()->user()->status==2){
            auth()->logout();

            return redirect()->route('login')->withMessage('Your account has been deleted. Please contact your administrator.');
        }

        return $next($request);
    }
}
