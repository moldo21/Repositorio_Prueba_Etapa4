<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PreferenciasController extends Controller
{
    public function edit(Request $request)
    {
        if (!Session::has('api_token')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a preferencias.');
        }

        $tema = $request->cookie('tema', 'claro');
        $moneda = $request->cookie('moneda', 'EUR');
        $paginacion = $request->cookie('paginacion', 6);

        return view('preferencias.edit', compact('tema', 'moneda', 'paginacion'));
    }

    public function update(Request $request)
    {
        if (!Session::has('api_token')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a preferencias.');
        }

        $request->validate([
            'tema' => 'required|in:claro,oscuro',
            'moneda' => 'required|in:EUR,USD,GBP',
            'paginacion' => 'required|in:6,12,24,48',
        ]);

        $minutos = 60 * 24 * 30;

        return redirect()
            ->route('preferencias.edit')
            ->with('success', 'Preferencias guardadas correctamente.')
            ->cookie('tema', $request->tema, $minutos)
            ->cookie('moneda', $request->moneda, $minutos)
            ->cookie('paginacion', $request->paginacion, $minutos);
    }
}
