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
        // Bypass for control endpoint or requests with SiteAgent headers
        // This prevents deadlocks where a suspended site blocks the "Un-suspend" command.
        if ($request->is('api/system/control') || $request->hasHeader('X-API-KEY')) {
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
