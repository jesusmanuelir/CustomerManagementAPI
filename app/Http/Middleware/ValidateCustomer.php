<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Rules\CommuneRegionRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

class ValidateCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rules = [
            "dni" => ["required", "unique:customers,dni"],
            "email" => ["required", "email", "unique:customers,email"],
            "name" => ["required"],
            "last_name" => ["required"],
            "address" => ["nullable"],
            "id_reg" => "required|exists:regions,id_reg",
            "id_com" => ["required", new CommuneRegionRule()],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }
        return $next($request);
    }
}
