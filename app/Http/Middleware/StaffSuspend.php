<?php

namespace App\Http\Middleware;
use Auth;

use Closure;

class StaffSuspend
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
        // dd($request->user()->status);
        if ($request->user()->status <> 'active') {

         Auth::logout();
        return redirect('/login')->with('error', 'UnAuthorized');          
    
        }

        return $next($request);
    }
}
