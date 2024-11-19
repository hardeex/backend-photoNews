<?php
namespace App\Http\Middleware;

use Closure;


use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class CheckJWTExpiration
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Attempt to parse and authenticate the user via the JWT token
            JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            // Handle token expiration or invalid token
            return response()->json([
                'status' => 'error',
                'message' => 'Session expired or invalid token. Please log in again.',
            ], 401); // 401 Unauthorized
        }

        // Proceed to the next middleware or request handler
        return $next($request);
    }
}
