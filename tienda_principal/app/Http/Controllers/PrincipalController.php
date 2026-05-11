<?php

namespace App\Http\Controllers;

use App\Services\MueblesApiService;
use Illuminate\Http\Request;

class PrincipalController extends Controller
{
    private MueblesApiService $mueblesApi;

    public function __construct(MueblesApiService $mueblesApi)
    {
        $this->mueblesApi = $mueblesApi;
    }

    public function index()
    {
        try {
            $catRes     = $this->mueblesApi->getCategorias();
            $categoriasData = [];
            if (isset($catRes['data'])) {
                $categoriasData = $catRes['data'];
            }
            $categorias = array_slice($categoriasData, 0, 2);

            $mueblesRes = $this->mueblesApi->getMuebles(['destacado' => true, 'por_pagina' => 6]);
            $productos = [];
            if (isset($mueblesRes['data'])) {
                $productos = $mueblesRes['data'];
            }
        } catch (\Exception $e) {
            $categorias = [];
            $productos  = [];
        }

        return view('principal', compact('categorias', 'productos'));
    }
}
