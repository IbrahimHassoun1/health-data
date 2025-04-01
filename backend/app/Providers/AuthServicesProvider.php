<?php

namespace App\Providers;


use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\ServiceProvider;
use Exception;

class AuthServicesProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {

    }

    public static function signup($userData)
    {
        try {
            $userData['password'] = bcrypt($userData['password']);
            $user = User::create($userData);
            $user['token'] = JWTAuth::fromUser($user);
            return $user;
        } catch (\Exception $e) {
            return response()->json([
                "message" => "error: " . $e->getMessage(),
            ]);
        }

    }

    public static function login($userData)
    {
        try {

            if (!$token = JWTAuth::attempt($userData)) {
                return response()->json([
                    "message" => "Invalid email or password",
                ], 401);
            }

            $user = JWTAuth::user();
            $user['token'] = $token;
            return $user;
        } catch (Exception $e) {
            return response()->json([
                "message" => "error: " . $e->getMessage(),
            ]);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
