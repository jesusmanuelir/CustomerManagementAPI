<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->only("email", "password");

            if (auth()->attempt($credentials)) {

                $user = auth()->user();
                $currentTime = Carbon::now()->timestamp;
                $tokenData = "{$user->email}|{$currentTime}|" . mt_rand(200, 500);
                $token = sha1($tokenData);

                $tokenExpirationMinutes = env("TOKEN_EXPIRATION_MINUTES");
                $expirationDate = Carbon::now()->addMinutes($tokenExpirationMinutes);

                DB::table("tokens")->insert([
                    "user_id" => auth()->user()->id,
                    "token" => $token,
                    "expires_at" => $expirationDate
                ]);

                return response()->json([
                    "success" => true,
                    "token" => $token,
                    "expires_at" => $expirationDate
                ], 200);
            }

            return response()->json([
               "success" => false,
               "message" =>"Credenciales inválidas"
           ], 401);

        } catch (\Exception$e) {
            return response()->json([
                "success" => false,
                "message"=> "Error interno del servidor: {$e->getMessage()}"
            ], 500);
       }
   }
}
