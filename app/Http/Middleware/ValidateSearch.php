<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateSearch
{
    public function handle(Request $request, Closure $next)
    {
        $dni = $request->input("dni");
        $email = $request->input("email");

        if (!$dni && !$email) {
            return response()->json([
                "success" => false,
                "message" => "Debes proporcionar al menos uno de los siguientes parámetros: dni o correo"
            ], 400);
        }

        return $next($request);
    }
}
