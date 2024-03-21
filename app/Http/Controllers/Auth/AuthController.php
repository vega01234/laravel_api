<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExternalUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\ExternalUser;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'external']]);
    }
    
    public function register (RegisterRequest $request)
    {        
        $user = User::create([
            'type_user' => $request->type_user,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'rol' => $request->rol,
            'token_time' => $request->token_time
        ]);

        $data = [
            'type_user' => $request->type_user,
            'rol' => $request->rol,
            'token_time' => $request->token_time
        ];

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

}
