<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class guestmiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Coba ambil user dari token
            if (JWTAuth::parseToken()->authenticate()) {
                return response()->json(['message' => 'Kamu sudah login.'], 403);
            }
        } catch (Exception $e) {
            return $next($request);
        }
        return $next($request);
    }
}
