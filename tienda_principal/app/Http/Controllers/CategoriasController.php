<?php
namespace App\Http\Controllers;

use App\Services\MueblesApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoriasController extends Controller
{
    private MueblesApiService $mueblesApi;

    public function __construct(MueblesApiService $mueblesApi)
    {
        $this->mueblesApi = $mueblesApi;
    }

    private function esAdmin(): bool
    {
        return Session::get('usuario_rol') === 'Administrador';
    }

    private function esGestorOAdmin(): bool
    {
        return in_array(Session::get('usuario_rol'), ['Administrador', 'Gestor']);
    }

    public function index()
    {
        try {
            $res = $this->mueblesApi->getCategorias();
            $categorias = [];
            if (isset($res['data'])) {
                $categorias = $res['data'];
            }
        } catch (\Exception $e) {
            $categorias = [];
        }
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        if (!$this->esGestorOAdmin()) {
            return redirect()->route('categorias.index')->with('error', 'No tienes permiso.');
        }
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        if (!$this->esGestorOAdmin()) {
            return redirect()->route('categorias.index')->with('error', 'No tienes permiso.');
        }

        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
        ]);

        try {
            $resultado = $this->mueblesApi->createCategoria($request->only('nombre', 'descripcion'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al conectar con la API.'])->withInput();
        }

        $exitoStore = false;
        if (isset($resultado['success'])) {
            $exitoStore = $resultado['success'];
        }
        if (!$exitoStore) {
            $errores = [];
            if (isset($resultado['errors'])) {
                $errores = $resultado['errors'];
            }
            if ($errores) {
                return back()->withErrors($errores)->withInput();
            } else {
                $mensajeError = 'Error al crear categoría.';
                if (isset($resultado['message'])) {
                    $mensajeError = $resultado['message'];
                }
                return back()->withErrors(['error' => $mensajeError])->withInput();
            }
        }

        return redirect()->route('categorias.index')->with('success', 'Categoría creada correctamente');
    }

    public function show($id)
    {
        return redirect()->route('productos.index', ['categoria' => $id]);
    }

    public function edit($id)
    {
        if (!$this->esGestorOAdmin()) {
            return redirect()->route('categorias.index')->with('error', 'No tienes permiso.');
        }

        try {
            $res = $this->mueblesApi->getCategoria($id);
            $categoria = null;
            if (isset($res['data'])) {
                $categoria = $res['data'];
            }
        } catch (\Exception $e) {
            abort(404);
        }

        if (!$categoria) {
            abort(404);
        }

        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        if (!$this->esGestorOAdmin()) {
            return redirect()->route('categorias.index')->with('error', 'No tienes permiso.');
        }

        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
        ]);

        try {
            $resultado = $this->mueblesApi->updateCategoria($id, $request->only('nombre', 'descripcion'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al conectar con la API.'])->withInput();
        }

        $exitoUpdate = false;
        if (isset($resultado['success'])) {
            $exitoUpdate = $resultado['success'];
        }
        if (!$exitoUpdate) {
            $mensajeError = 'Error al actualizar.';
            if (isset($resultado['message'])) {
                $mensajeError = $resultado['message'];
            }
            return back()->withErrors(['error' => $mensajeError])->withInput();
        }

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente');
    }

    public function destroy($id)
    {
        if (!$this->esAdmin()) {
            return redirect()->route('categorias.index')->with('error', 'No tienes permiso.');
        }

        try {
            $this->mueblesApi->deleteCategoria($id);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar categoría.']);
        }

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada correctamente');
    }
}
