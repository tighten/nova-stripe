<?php

namespace Tighten\NovaStripe\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Tighten\NovaStripe\NovaStripe;

class Authorize
{
    public function handle(Request $request, $next): Response
    {
        $tool = collect(Nova::registeredTools())->first($this->matchesTool(...));

        return optional($tool)->authorize($request) ? $next($request) : abort(403);
    }

    public function matchesTool(Tool $tool): bool
    {
        return $tool instanceof NovaStripe;
    }
}
