<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Abilities por rol
    private function abilitiesPorRol(string $rol): array
    {
        return match ($rol) {
            'Administrador' => [
                'perfil.ver',
                'usuarios.ver',
                'usuarios.crear',
                'usuarios.editar',
                'usuarios.eliminar',
                'muebles.ver',
                'muebles.crear',
                'muebles.editar',
                'muebles.eliminar',
                'admin.panel',
            ],
            'Gestor' => [
                'perfil.ver',
                'muebles.ver',
                'muebles.crear',
                'muebles.editar',
                'muebles.eliminar',
            ],
            default => [ // Cliente
                'perfil.ver',
                'muebles.ver',
                'carrito.gestionar',
                'pedidos.crear',
            ],
        };
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $usuario = User::create([
            'nombre'    => $request->nombre,
            'apellidos' => $request->apellidos,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'rol_id'    => 3, // Cliente siempre
        ]);

        $usuario->load('rol');
        $abilities = $this->abilitiesPorRol($usuario->rol->nombre);
        $token = $usuario->createToken('auth_token', $abilities)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado correctamente.',
            'data'    => new UserResource($usuario),
            'token'   => $token,
            'abilities' => $abilities,
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $usuario = User::with('rol')->where('email', $request->email)->first();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas.',
            ], 401);
        }

        // Comprobar si está bloqueado
        if ($usuario->bloqueado_hasta && now()->lessThan($usuario->bloqueado_hasta)) {
            return response()->json([
                'success' => false,
                'message' => 'Cuenta bloqueada temporalmente. Inténtalo más tarde.',
            ], 403);
        }

        // Desbloquear si ya pasó el tiempo
        if ($usuario->bloqueado_hasta && now()->greaterThanOrEqualTo($usuario->bloqueado_hasta)) {
            $usuario->intentos_fallidos = 0;
            $usuario->bloqueado_hasta   = null;
            $usuario->save();
        }

        if (!Hash::check($request->password, $usuario->password)) {
            $usuario->intentos_fallidos += 1;
            if ($usuario->intentos_fallidos >= 3) {
                $usuario->bloqueado_hasta = now()->addMinutes(5);
            }
            $usuario->save();

            $restantes = max(3 - $usuario->intentos_fallidos, 0);
            return response()->json([
                'success' => false,
                'message' => "Credenciales incorrectas. Intentos restantes: {$restantes}.",
            ], 401);
        }

        // Login correcto: resetear intentos
        $usuario->intentos_fallidos = 0;
        $usuario->bloqueado_hasta   = null;
        $usuario->save();

        // Revocar tokens anteriores y crear uno nuevo
        $usuario->tokens()->delete();
        $abilities = $this->abilitiesPorRol($usuario->rol->nombre);
        $token = $usuario->createToken('auth_token', $abilities)->plainTextToken;

        return response()->json([
            'success'   => true,
            'message'   => 'Login correcto.',
            'data'      => new UserResource($usuario),
            'token'     => $token,
            'abilities' => $abilities,
        ]);
    }

    public function perfil(Request $request): JsonResponse
    {
        $usuario = $request->user()->load('rol');

        return response()->json([
            'success' => true,
            'data'    => new UserResource($usuario),
        ]);
    }

    public function tokenAbilities(Request $request): JsonResponse
    {
        $abilities = [];

        $token = $request->user()->currentAccessToken();
        if ($token && is_array($token->abilities)) {
            $abilities = $token->abilities;
        }

        return response()->json([
            'success' => true,
            'data'    => $abilities,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }
}
