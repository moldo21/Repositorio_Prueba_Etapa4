@extends('cabecera')

@section('contenido')
    @php
        $galerias = collect($producto['galerias'] ?? [])->sortBy('orden')->values()->all();
        $moneda = Cookie::get('moneda', 'EUR');
        $simbolo = $moneda === 'USD' ? '$' : ($moneda === 'GBP' ? '£' : '€');
        $simboloDerecha = $moneda === 'EUR';
    @endphp

    <div class="row">

        <div class="col-md-6">
            <div id="carouselProducto" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @if (count($galerias) > 0)
                        @foreach ($galerias as $index => $galeria)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <img src="{{ $galeria['url'] ?? asset('imagenes/' . $galeria['ruta']) }}" class="d-block w-100 rounded shadow"
                                    alt="{{ $producto['nombre'] }}" style="height: 400px; object-fit: cover;">
                            </div>
                        @endforeach
                    @else
                        <div class="carousel-item active">
                            <div class="d-block w-100 rounded shadow bg-light d-flex align-items-center justify-content-center"
                                style="height: 400px;">
                                <span class="text-muted">Sin imágenes</span>
                            </div>
                        </div>
                    @endif
                </div>
                @if (count($galerias) > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselProducto"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselProducto"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                @endif
            </div>

            @if (count($galerias) > 1)
                <div class="d-flex gap-2 mt-3 overflow-auto">
                    @foreach ($galerias as $index => $galeria)
                        <img src="{{ $galeria['url'] ?? asset('imagenes/' . $galeria['ruta']) }}" class="img-thumbnail"
                            style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                            data-bs-target="#carouselProducto" data-bs-slide-to="{{ $index }}">
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <h2>{{ $producto['nombre'] }}</h2>

            @if ($producto['destacado'] ?? false)
                <span class="badge bg-warning text-dark mb-2">
                    <i class="bi bi-star-fill"></i> Destacado
                </span>
            @endif

            <p class="text-muted">
                <strong>Categoría:</strong> {{ $producto['categoria']['nombre'] ?? 'Sin categoría' }}
            </p>

            <p>{{ $producto['descripcion'] }}</p>

            <ul class="list-group mb-3">
                <li class="list-group-item">
                    <strong>Precio:</strong> {{ $simboloDerecha ? number_format($producto['precio'], 2) . ' ' . $simbolo : $simbolo . number_format($producto['precio'], 2) }}
                </li>
                <li class="list-group-item">
                    <strong>Stock:</strong> {{ $producto['stock'] }} unidades
                </li>
                <li class="list-group-item">
                    <strong>Materiales:</strong> {{ $producto['materiales'] }}
                </li>
                <li class="list-group-item">
                    <strong>Dimensiones:</strong> {{ $producto['dimensiones'] }}
                </li>
                <li class="list-group-item">
                    <strong>Color principal:</strong> {{ $producto['color_principal'] }}
                </li>
            </ul>

            <div class="d-grid gap-2">
                @if (session()->has('api_token'))
                    @if (($producto['stock'] ?? 0) > 0)
                        <form method="POST" action="{{ route('carrito.add', $producto['id']) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Añadir al Carrito
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary btn-lg" disabled>
                            Sin stock disponible
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                        Añadir al Carrito
                    </a>
                @endif

                <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
                    ← Volver al catálogo
                </a>

                @if (session()->has('api_token') && in_array(session('usuario_rol'), ['Administrador', 'Gestor']))
                    <hr>
                    <a href="{{ route('productos.edit', $producto['id']) }}" class="btn btn-warning">
                        Editar producto
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
