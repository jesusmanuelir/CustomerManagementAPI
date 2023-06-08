<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequestResponse
{
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->getClientIp();

        Log::channel('request_response')->info("Entrada | IP: {$clientIp} | URL: {$request->fullUrl()} | Método: {$request->method()}");

        $response = $next($request);

          Log::channel('request_response')->info("Salida  | IP: {$clientIp} | URL: {$response->headers->get('location', '-')}");

          return $response;
    }
}
