<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerificarTokenExterno
{
    public function handle(Request $request, Closure $next, string ...$abilities)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token de autenticación requerido.',
            ], 401);
        }

        // Verificar token contra api_usuarios
        $apiUsuariosUrl = config('services.api_usuarios.url');

        try {
            $response = Http::withToken($token)
                ->timeout(10)
                ->get($apiUsuariosUrl . '/perfil');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo verificar el token. Servicio de usuarios no disponible.',
            ], 503);
        }

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido o expirado.',
            ], 401);
        }

        $userData = $response->json('data');

        // Verificar abilities si se requieren
        if (!empty($abilities)) {
            try {
                $abilitiesResponse = Http::withToken($token)
                    ->timeout(10)
                    ->get($apiUsuariosUrl . '/token-abilities');
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudieron verificar los permisos del token.',
                ], 503);
            }

            if (!$abilitiesResponse->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudieron verificar los permisos del token.',
                ], 503);
            }

            $abilitiesDelToken = [];
            $abilitiesData = $abilitiesResponse->json('data');
            if (is_array($abilitiesData)) {
                $abilitiesDelToken = $abilitiesData;
            }

            foreach ($abilities as $ability) {
                if (!in_array($ability, $abilitiesDelToken, true)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes permisos para realizar esta acción.',
                    ], 403);
                }
            }
        }

        // Inyectar datos del usuario en la request
        $request->merge(['_usuario_externo' => $userData]);
        $request->attributes->set('usuario_externo', $userData);

        return $next($request);
    }
}
