<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIsAdminOrSelf
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
        $requestedUserId = $request->route()->parameter('id');
        if(in_array(Auth::user()->role,['superadmin','admin']) || Auth::user()->id == $requestedUserId) return $next($request);
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
