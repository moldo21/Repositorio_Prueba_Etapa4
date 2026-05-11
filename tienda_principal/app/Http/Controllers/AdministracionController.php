<?php

namespace App\Http\Controllers;

use App\Services\MueblesApiService;
use App\Services\UsuarioApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

// Panel de administración — agrega datos de ambas APIs

class AdministracionController extends Controller
{
    private UsuarioApiService $usuarioApi;
    private MueblesApiService $mueblesApi;

    public function __construct(UsuarioApiService $usuarioApi, MueblesApiService $mueblesApi)
    {
        $this->usuarioApi = $usuarioApi;
        $this->mueblesApi = $mueblesApi;
    }

    public function index()
    {
        if (Session::get('usuario_rol') !== 'Administrador') {
            return redirect()->route('principal')->with('error', 'Acceso restringido.');
        }

        try {
            $usuariosRes  = $this->usuarioApi->getUsuarios();
            $usuarios = [];
            if (isset($usuariosRes['data'])) {
                $usuarios = $usuariosRes['data'];
            }
        } catch (\Exception $e) {
            $usuarios = [];
        }

        try {
            $mueblesRes = $this->mueblesApi->getMuebles(['por_pagina' => 50]);
            $muebles = [];
            if (isset($mueblesRes['data'])) {
                $muebles = $mueblesRes['data'];
            }
            $catRes = $this->mueblesApi->getCategorias();
            $categorias = [];
            if (isset($catRes['data'])) {
                $categorias = $catRes['data'];
            }
        } catch (\Exception $e) {
            $muebles    = [];
            $categorias = [];
        }

        return view('administracion.index', compact('usuarios', 'muebles', 'categorias'));
    }
}
