<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\CarritoItem;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Services\MueblesApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CarritoController extends Controller
{
    private MueblesApiService $mueblesApi;

    public function __construct(MueblesApiService $mueblesApi)
    {
        $this->mueblesApi = $mueblesApi;
    }

    private function estaLogueado(): bool
    {
        return Session::has('api_token') && Session::has('usuario_id');
    }

    private function usuarioId(): int
    {
        return (int) Session::get('usuario_id');
    }

    private function obtenerCarrito(): Carrito
    {
        $carrito = Carrito::where('usuario_id', $this->usuarioId())->latest()->first();

        if (!$carrito) {
            $carrito = Carrito::create([
                'usuario_id' => $this->usuarioId(),
                'sesionId'   => Session::getId(),
            ]);
        }

        return $carrito;
    }

    public function show()
    {
        if (!$this->estaLogueado()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver el carrito');
        }

        $carrito = $this->obtenerCarrito();
        $items   = CarritoItem::where('carrito_id', $carrito->id)->get();

        $subtotal  = 0;
        foreach ($items as $item) {
            $subtotal += $item->cantidad * $item->precio_unitario;
        }

        $impuestos = $subtotal * 0.10;
        $total     = $subtotal + $impuestos;

        return view('carrito.index', compact('carrito', 'items', 'subtotal', 'impuestos', 'total'));
    }

    public function add(Request $request, $productoId)
    {
        if (!$this->estaLogueado()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        // Obtener datos del producto desde la API
        try {
            $res      = $this->mueblesApi->getMueble($productoId);
            $producto = null;
            if (isset($res['data'])) {
                $producto = $res['data'];
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo cargar el producto.');
        }

        if (!$producto) {
            return redirect()->back()->with('error', 'Producto no encontrado.');
        }

        $carrito = $this->obtenerCarrito();

        $item = CarritoItem::where('carrito_id', $carrito->id)
            ->where('producto_id', $productoId)
            ->first();

        if ($item) {
            if ($item->cantidad + 1 > $producto['stock']) {
                return redirect()->back()
                    ->with('error', "No hay suficiente stock de '{$producto['nombre']}'. Disponible: {$producto['stock']}");
            }
            $item->cantidad += 1;
            $item->save();
        } else {
            if ($producto['stock'] < 1) {
                return redirect()->back()
                    ->with('error', "No hay stock disponible de '{$producto['nombre']}'");
            }            $imagenProducto = null;
            if (isset($producto['imagen_principal'])) {
                $imagenProducto = $producto['imagen_principal'];
            }            CarritoItem::create([
                'carrito_id'       => $carrito->id,
                'producto_id'      => $productoId,
                'nombre_producto'  => $producto['nombre'],
                'imagen_producto'  => $imagenProducto,
                'cantidad'         => 1,
                'precio_unitario'  => $producto['precio'],
            ]);
        }

        return redirect()->back()->with('success', "'{$producto['nombre']}' añadido al carrito");
    }

    public function aumentar($itemId)
    {
        if (!$this->estaLogueado()) {
            return redirect()->route('login');
        }

        $item = CarritoItem::findOrFail($itemId);

        if ($item->carrito->usuario_id !== $this->usuarioId()) {
            return redirect()->back()->with('error', 'Acción no permitida');
        }

        // Verificar stock desde la API
        try {
            $res      = $this->mueblesApi->getMueble($item->producto_id);
            $producto = null;
            if (isset($res['data'])) {
                $producto = $res['data'];
            }
            if ($producto && $item->cantidad + 1 > $producto['stock']) {
                return redirect()->back()->with('error', "No hay suficiente stock de '{$item->nombre_producto}'");
            }
        } catch (\Exception $e) {
            // Si la API no responde, permitir la operación
        }

        $item->cantidad += 1;
        $item->save();

        return redirect()->back()->with('success', 'Cantidad actualizada');
    }

    public function disminuir($itemId)
    {
        if (!$this->estaLogueado()) {
            return redirect()->route('login');
        }

        $item = CarritoItem::findOrFail($itemId);

        if ($item->carrito->usuario_id !== $this->usuarioId()) {
            return redirect()->back()->with('error', 'Acción no permitida');
        }

        if ($item->cantidad > 1) {
            $item->cantidad -= 1;
            $item->save();
        } else {
            $item->delete();
        }

        return redirect()->back()->with('success', 'Cantidad actualizada');
    }

    public function remove($itemId)
    {
        if (!$this->estaLogueado()) {
            return redirect()->route('login');
        }

        $item = CarritoItem::findOrFail($itemId);

        if ($item->carrito->usuario_id !== $this->usuarioId()) {
            return redirect()->back()->with('error', 'Acción no permitida');
        }

        $nombre = $item->nombre_producto;
        $item->delete();

        return redirect()->route('carrito.show')->with('success', "'{$nombre}' eliminado del carrito");
    }

    public function clear()
    {
        if (!$this->estaLogueado()) {
            return redirect()->route('login');
        }

        $carrito = $this->obtenerCarrito();
        CarritoItem::where('carrito_id', $carrito->id)->delete();

        return redirect()->route('carrito.show')->with('success', 'Carrito vaciado correctamente');
    }

    public function comprar()
    {
        if (!$this->estaLogueado()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        $carrito = $this->obtenerCarrito();
        $items   = CarritoItem::where('carrito_id', $carrito->id)->get();

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'El carrito está vacío');
        }

        // Validar stock de cada producto contra la API
        foreach ($items as $item) {
            try {
                $res      = $this->mueblesApi->getMueble($item->producto_id);
                $producto = null;
                if (isset($res['data'])) {
                    $producto = $res['data'];
                }
                if ($producto && $item->cantidad > $producto['stock']) {
                    return redirect()->back()
                        ->with('error', "Stock insuficiente para '{$item->nombre_producto}'");
                }
            } catch (\Exception $e) {
                // Continuar si la API no responde
            }
        }

        // Calcular totales
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item->cantidad * $item->precio_unitario;
        }
        $impuestos = round($subtotal * 0.10, 2);
        $total     = round($subtotal + $impuestos, 2);

        // Guardar pedido
        $pedido = Pedido::create([
            'usuario_id' => $this->usuarioId(),
            'estado'     => 'completado',
            'subtotal'   => $subtotal,
            'impuestos'  => $impuestos,
            'total'      => $total,
        ]);

        foreach ($items as $item) {
            PedidoItem::create([
                'pedido_id'       => $pedido->id,
                'producto_id'     => $item->producto_id,
                'nombre_producto' => $item->nombre_producto,
                'imagen_producto' => $item->imagen_producto,
                'cantidad'        => $item->cantidad,
                'precio_unitario' => $item->precio_unitario,
            ]);
        }

        CarritoItem::where('carrito_id', $carrito->id)->delete();

        return redirect()->route('principal')->with('success', '¡Compra realizada con éxito! Pedido #' . $pedido->id);
    }
}
