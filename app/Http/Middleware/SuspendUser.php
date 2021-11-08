<?php

namespace App\Http\Middleware;

use Closure;

class SuspendUser
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
        if ($request->user()->status <> 'active' || $request->user()->admin <> 0) {

          return response()->json(['error'=>'Unauthorised'], 401);
    
        }

        return $next($request);
    }
}
