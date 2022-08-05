<?php

namespace App\Http\Middleware;

use Closure;

class ThrottleRequestsWithIp extends \Illuminate\Routing\Middleware\ThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        if($request->ip() === '94.250.254.187')
            return $next($request);

        return parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix);
    }
}
