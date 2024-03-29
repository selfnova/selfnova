<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserHasBan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
		if ( $request->user()->is_ban) {
            abort(403, 'Access denied');
        }
        return $next($request);
    }
}
