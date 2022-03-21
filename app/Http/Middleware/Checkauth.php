<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Checkauth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth('api')->check())
        return $next($request);
        else{
            return response()->json(['success'=>false,"data" => [], "status" => 403, 'message' => "Token must be correctly sent"]);
        }    }
}
