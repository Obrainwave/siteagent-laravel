<?php

namespace ZuqoLab\SiteAgent\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use ZuqoLab\SiteAgent\StateManager;
use Symfony\Component\HttpFoundation\Response;

class EnforceSiteAgent
{
    public function __construct(protected StateManager $stateManager)
    {
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Bypass for control endpoint
        if ($request->is('api/system/control')) {
            return $next($request);
        }

        // Bypass for health checks
        if ($request->is('health')) {
            return $next($request);
        }

        // Check if site is suspended
        if ($this->stateManager->isSuspended()) {
            return response()->view('siteagent::suspended', [], 503);
        }

        return $next($request);
    }
}
