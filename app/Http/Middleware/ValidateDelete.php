<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateDelete
{
    public function handle(Request $request, Closure $next)
    {
        $dni = $request->route('dni');

        if (empty($dni)) {
            return response()->json([
                "success" => false,
                "message" => "El parámetro dni es obligatorio."
            ], 400);
        }

        return $next($request);
    }
}
