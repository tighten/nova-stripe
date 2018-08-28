<?php

namespace Tightenco\NovaStripe\Http\Middleware;

use Tightenco\NovaStripe\NovaStripe;

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
