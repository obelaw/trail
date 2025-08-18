<?php

namespace Obelaw\Trail\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Obelaw\Trail\Support\RequestContext;

class CaptureRequestContext
{
    public function handle(Request $request, Closure $next)
    {
        RequestContext::capture($request);

        return $next($request);
    }
}
