<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_active === false) {
            throw new AccessDeniedHttpException('تم إيقاف حسابك من قبل الإدارة');
        }

        return $next($request);
    }
}
