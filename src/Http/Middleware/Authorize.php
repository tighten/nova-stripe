<?php

namespace Tighten\NovaStripe\Http\Middleware;

use Tighten\NovaStripe\NovaStripe;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(NovaStripe::class)->authorize($request) ? $next($request) : abort(403);
    }
}
