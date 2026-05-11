<?php

namespace App\Http\Controllers;

use App\Services\UsuarioApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PerfilController extends Controller
{
    private UsuarioApiService $usuarioApi;

    public function __construct(UsuarioApiService $usuarioApi)
    {
        $this->usuarioApi = $usuarioApi;
    }

    public function show()
    {
        if (!Session::has('api_token')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        try {
            $resultado = $this->usuarioApi->perfil();
            $usuario = null;
            if (isset($resultado['data'])) {
                $usuario = $resultado['data'];
            }
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo cargar el perfil.');
        }

        return view('perfil.show', compact('usuario'));
    }

    public function edit()
    {
        if (!Session::has('api_token')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        try {
            $resultado = $this->usuarioApi->perfil();
            $usuario = null;
            if (isset($resultado['data'])) {
                $usuario = $resultado['data'];
            }
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo cargar el perfil.');
        }

        return view('perfil.edit', compact('usuario'));
    }

    public function update(Request $request)
    {
        if (!Session::has('api_token')) {
            return redirect()->route('login');
        }

        $request->validate([
            'nombre'    => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
        ]);

        $id = Session::get('usuario_id');

        try {
            $resultado = $this->usuarioApi->updateUsuario($id, $request->only(['nombre', 'apellidos']));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar el perfil.']);
        }

        $exitoPerfil = false;
        if (isset($resultado['success'])) {
            $exitoPerfil = $resultado['success'];
        }
        if (!$exitoPerfil) {
            $mensajeError = 'Error.';
            if (isset($resultado['message'])) {
                $mensajeError = $resultado['message'];
            }
            return back()->withErrors(['error' => $mensajeError]);
        }

        // Actualizar nombre en sesión
        Session::put('usuario_nombre', $request->nombre);

        return redirect()->route('perfil.show')->with('success', 'Perfil actualizado correctamente.');
    }
}
