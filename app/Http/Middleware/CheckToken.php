<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header("Authorization");

        if (!$token) {
            return response()->json([
                "success" => false,
                "message" => "No se proporcionó el token"
            ], 401);
        }

        if (substr($token, 0, 7) === "Bearer ") {
            $token = substr($token, 7);
        }

    $now = now();
    $record = DB::table("tokens")->where("token", "=", $token)->first();

    if (!$record || ($record->expires_at && strtotime($record->expires_at) < strtotime($now))) {
        return response()->json([
            "success" => false,
            "message" => "El token proporcionado es inválido o ha expirado"
        ], 403);
    }

    return $next($request);
    }
}
