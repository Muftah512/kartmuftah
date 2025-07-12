<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string   $role
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        // ÊÃßÏ ãä ÊÓÌíá ÇáÏÎæá æÃä ááÏæÑ ÇáãØáæÈ
        if (! $user || ! $user->hasRole($role)) {
            abort(403, 'ÛíÑ ãÎæøá ÈÇáÏÎæá Åáì åĞå ÇáÕİÍÉ.');
        }

        return $next($request);
    }
}
