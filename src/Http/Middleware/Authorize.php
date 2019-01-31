<?php

namespace Dniccum\CustomEmailSender\Http\Middleware;

use Dniccum\CustomEmailSender\CustomEmailSender;

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
        return resolve(CustomEmailSender::class)->authorize($request) ? $next($request) : abort(403);
    }
}
