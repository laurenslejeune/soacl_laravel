<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Log;
use Closure;

/*
 * Source:
 * https://medium.com/@neo/handling-xmlhttprequest-options-pre-flight-request-in-laravel-a4c4322051b9
 * 
 */

class PreflightResponse
{
    /**
    * Handle an incoming request.
    *
    * @param \Illuminate\Http\Request $request
    * @param \Closure $next
    * @return mixed
    */
    public function handle($request, Closure $next )
    {
        if ($request->getMethod() === "OPTIONS")
        {
            Log::info('responding to an OPTIONS request');
            return response('');
        }

        return $next($request);
     }
 }