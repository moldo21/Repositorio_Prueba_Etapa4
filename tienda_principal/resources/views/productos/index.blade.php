@extends('cabecera')

@section('contenido')

    <h2 class="mb-4">Catálogo de Productos</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="GET" action="{{ route('productos.index') }}" class="row g-3 mb-4">
        <div class="col-md-2">
            <label for="categoria" class="form-label">Categoría</label>
            <select name="categoria" id="categoria" class="form-select">
                <option value="">Todas</option>
                @foreach ($categorias as $c)
                    <option value="{{ $c['id'] }}" {{ request('categoria') == $c['id'] ? 'selected' : '' }}>
                        {{ $c['nombre'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="min" class="form-label">Precio min</label>
            <input type="number" name="min" id="min" class="form-control" step="0.01"
                value="{{ request('min') }}" placeholder="0">
        </div>

        <div class="col-md-2">
            <label for="max" class="form-label">Precio max</label>
            <input type="number" name="max" id="max" class="form-control" step="0.01"
                value="{{ request('max') }}" placeholder="1000">
        </div>

        <div class="col-md-2">
            <label for="color" class="form-label">Color</label>
            <input type="text" name="color" id="color" class="form-control" value="{{ request('color') }}"
                placeholder="Ej. Gris">
        </div>

        <div class="col-md-2">
            <label for="busqueda" class="form-label">Buscar</label>
            <input type="text" name="busqueda" id="busqueda" class="form-control" value="{{ request('busqueda') }}"
                placeholder="Nombre o descripción">
        </div>

        <div class="col-12 mt-2">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="mb-3">
        <strong>Ordenar por:</strong>
        @php
            $dir = request('dir', 'asc') === 'asc' ? 'desc' : 'asc';
        @endphp
        <a href="{{ route('productos.index', array_merge(request()->query(), ['orden' => 'precio', 'dir' => $dir])) }}">
            Precio @if (request('orden') === 'precio') {{ request('dir') === 'asc' ? '↑' : '↓' }} @endif
        </a> |
        <a href="{{ route('productos.index', array_merge(request()->query(), ['orden' => 'nombre', 'dir' => $dir])) }}">
            Nombre @if (request('orden') === 'nombre') {{ request('dir') === 'asc' ? '↑' : '↓' }} @endif
        </a> |
        <a href="{{ route('productos.index', array_merge(request()->query(), ['orden' => 'novedad', 'dir' => $dir])) }}">
            Novedad @if (request('orden') === 'novedad') {{ request('dir') === 'asc' ? '↑' : '↓' }} @endif
        </a>
    </div>

    @php
        $moneda = Cookie::get('moneda', 'EUR');
        $simbolo = $moneda === 'USD' ? '$' : ($moneda === 'GBP' ? '£' : '€');
        $simboloDerecha = $moneda === 'EUR';
    @endphp

    <div class="row">
        @forelse ($productos as $producto)
            <div class="col-md-3 mb-3">
                <div class="card h-100">

                    @if ($producto['imagen_principal'] ?? null)
                        <img src="{{ $producto['imagen_url'] ?? asset('imagenes/' . $producto['imagen_principal']) }}" class="card-img-top"
                            alt="{{ $producto['nombre'] }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                            style="height: 200px;">
                            <span class="text-muted">Sin imagen</span>
                        </div>
                    @endif

                    <div class="card-body text-center d-flex flex-column">
                        <h5 class="card-title">{{ $producto['nombre'] }}</h5>
                        <p class="text-muted small flex-grow-1">{{ Str::limit($producto['descripcion'], 60) }}</p>
                        <p class="mb-3"><strong>{{ $simboloDerecha ? number_format($producto['precio'], 2) . ' ' . $simbolo : $simbolo . number_format($producto['precio'], 2) }}</strong></p>

                        <div class="d-grid gap-2">
                            @if (session()->has('api_token'))
                                <form method="POST" action="{{ route('carrito.add', $producto['id']) }}">
                                    @csrf
                                    <button class="btn btn-primary w-100" type="submit">
                                        Añadir al Carrito
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">
                                    Añadir al Carrito
                                </a>
                            @endif

                            <a href="{{ route('productos.show', $producto['id']) }}" class="btn btn-outline-secondary">
                                Ver detalles
                            </a>

                            @if (session()->has('api_token') && in_array(session('usuario_rol'), ['Administrador', 'Gestor']))
                                <a href="{{ route('productos.edit', $producto['id']) }}" class="btn btn-warning btn-sm">
                                    Editar
                                </a>
                                @if (session('usuario_rol') === 'Administrador')
                                <form action="{{ route('productos.destroy', $producto['id']) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm w-100"
                                        onclick="return confirm('¿Seguro que deseas eliminar este producto?');">
                                        Eliminar
                                    </button>
                                </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">No se encontraron productos que coincidan con los filtros.</p>
        @endforelse
    </div>

    {{-- Paginación manual basada en $meta de la API --}}
    @if (($meta['last_page'] ?? 1) > 1)
        @php
            $currentPage = $meta['current_page'] ?? 1;
            $lastPage    = $meta['last_page'] ?? 1;
            $queryBase   = array_merge(request()->query(), []);
        @endphp
        <div class="mt-4">
            <nav>
                <ul class="pagination justify-content-center">

                    @if ($currentPage <= 1)
                        <li class="page-item disabled"><span class="page-link">« Anterior</span></li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ route('productos.index', array_merge($queryBase, ['page' => $currentPage - 1])) }}">« Anterior</a>
                        </li>
                    @endif

                    @for ($p = 1; $p <= $lastPage; $p++)
                        @if ($p == $currentPage)
                            <li class="page-item active"><span class="page-link">{{ $p }}</span></li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ route('productos.index', array_merge($queryBase, ['page' => $p])) }}">{{ $p }}</a>
                            </li>
                        @endif
                    @endfor

                    @if ($currentPage >= $lastPage)
                        <li class="page-item disabled"><span class="page-link">Siguiente »</span></li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ route('productos.index', array_merge($queryBase, ['page' => $currentPage + 1])) }}">Siguiente »</a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    @endif

    <hr>
    <div class="text-center mt-3">
        <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary">
            ← Volver a Categorías
        </a>
    </div>
@endsection
