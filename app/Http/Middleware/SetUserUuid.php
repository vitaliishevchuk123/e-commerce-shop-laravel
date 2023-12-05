<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class SetUserUuid
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasCookie('uuid')) {
            return $next($request);
        }

        return $next($request)->withCookie(cookie()->forever('uuid', Uuid::uuid4()->toString()));
    }
}
