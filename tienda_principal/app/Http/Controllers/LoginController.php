<?php

namespace App\Http\Controllers;

use App\Services\UsuarioApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    private UsuarioApiService $usuarioApi;

    public function __construct(UsuarioApiService $usuarioApi)
    {
        $this->usuarioApi = $usuarioApi;
    }

    public function show()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:4',
        ]);

        try {
            $resultado = $this->usuarioApi->login($request->email, $request->password);
        } catch (\Exception $e) {
            return back()->withErrors(['errorCredenciales' => 'No se pudo conectar con el servicio de usuarios.']);
        }

        $exitoLogin = false;
        if (isset($resultado['success'])) {
            $exitoLogin = $resultado['success'];
        }
        if (!$exitoLogin) {
            $mensaje = 'Credenciales incorrectas.';
            if (isset($resultado['message'])) {
                $mensaje = $resultado['message'];
            }
            return back()->withErrors(['errorCredenciales' => $mensaje]);
        }

        $usuario   = $resultado['data'];
        $token     = $resultado['token'];
        $abilities = [];
        if (isset($resultado['abilities'])) {
            $abilities = $resultado['abilities'];
        }

        // Guardar datos en sesión (no usamos BD local para autenticación)
        Session::put('api_token',      $token);
        Session::put('usuario_id',     $usuario['id']);
        Session::put('usuario_nombre', $usuario['nombre']);
        Session::put('usuario_email',  $usuario['email']);
        $rolNombre = 'Cliente';
        if (isset($usuario['rol']['nombre'])) {
            $rolNombre = $usuario['rol']['nombre'];
        }
        Session::put('usuario_rol',    $rolNombre);
        Session::put('abilities',      $abilities);
        Session::put('login_at',       now()->toDateTimeString());

        return redirect()->route('principal');
    }

    public function logout(Request $request)
    {
        if (Session::has('api_token')) {
            try {
                $this->usuarioApi->logout();
            } catch (\Exception $e) {
                // Si la API no responde, cerramos sesión local igualmente
            }
        }

        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('principal')->with('success', 'Sesión cerrada.');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email'     => 'required|email',
            'password'  => 'required|min:4|confirmed',
        ]);

        try {
            $resultado = $this->usuarioApi->register($request->only(['nombre', 'apellidos', 'email', 'password', 'password_confirmation']));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'No se pudo conectar con el servicio de usuarios.']);
        }

        $exitoRegistro = false;
        if (isset($resultado['success'])) {
            $exitoRegistro = $resultado['success'];
        }
        if (!$exitoRegistro) {
            $errores = [];
            if (isset($resultado['errors'])) {
                $errores = $resultado['errors'];
            }
            if ($errores) {
                return back()->withErrors($errores)->withInput();
            }
            $mensajeError = 'Error en el registro.';
            if (isset($resultado['message'])) {
                $mensajeError = $resultado['message'];
            }
            return back()->withErrors(['error' => $mensajeError])->withInput();
        }

        return redirect()->route('login')->with('success', 'Cuenta creada. Ya puedes iniciar sesión.');
    }
}
