<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\PersonalAccessToken;

class ApiAuthMiddleware
{
    /**
     * Manejar una solicitud entrante.
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $token = $request->bearerToken(); // Obtiene el token del encabezado "Authorization"

        if (!$token) {
            return response()->json([
                'message' => 'Token no proporcionado. Por favor, inicie sesión.',
            ], 401);
        }

        // Validar el token en la base de datos
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json([
                'message' => 'Token inválido o expirado.',
            ], 401);
        }

        // Autenticar al usuario asociado al token
        auth()->setUser($accessToken->tokenable);

        return $next($request);
    }
}
