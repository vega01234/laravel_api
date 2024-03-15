<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rol = ['admin'];
        try {
            $token = JWTAuth::parseToken();
            $user = $token->authenticate();
        } catch (TokenInvalidException $e) {
            return $this->unauthorized('Invalid Token');
        } catch (TokenExpiredException $e) {
            return $this->unauthorized('Expired Token');
        } catch (JWTException $e) {
            return $this->unauthorized('Token Not Found');
        }
        if ($user && in_array($user->rol, $rol)) {
            return $next($request);
        }
        return $this->unauthorized();
    }

    private function unauthorized($message = null) {
        return response()->json([
            'message' => $message ? $message: 'You Are Unauthorized to Access This Resource',
            'status' => false
        ], 401);
    }

}
