<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class BasicAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if ($token === config('app.api_key')) {
            return $next($request);
        }

        throw new AuthenticationException("Invalid bearer token");
    }
}
