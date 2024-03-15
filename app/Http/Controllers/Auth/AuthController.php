<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExternalUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'rol' => $request->rol
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Acount Created Successfully!',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'Invalid Credentials'
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Not Created Token'
            ]);
        }
        return response()->json([
            'message' => 'Logged Successfully!', 
            'data_user' => compact('token')
        ]);
    }

    public function external(ExternalUserRequest $request)
    {
        $externalUser = [
            'rol' => $request->rol,
            'token_time' => $request->token_time
        ];

        JWTAuth::factory()->setTTL($request->token_time);
        $token = JWTAuth::fromUser($externalUser);

        return response()->json([
            'message' => 'Anonymous User Created Successfully!',
            'user' => $externalUser,
            'token' => $token
        ]);
    }

}
