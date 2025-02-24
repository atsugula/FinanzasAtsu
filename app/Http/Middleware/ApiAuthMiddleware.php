<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApiAuthMiddleware
{
    /**
     * Manejar una solicitud entrante.
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'No autenticado. Por favor, inicie sesión.',
                'headers' => $request->headers->all(),
                'authorization' => $request->header('Authorization'),
            ], 401);
        }

        return $next($request);
    }
}
