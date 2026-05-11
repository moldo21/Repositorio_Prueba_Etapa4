@extends('cabecera')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/principal.css') }}">
@endpush

@section('contenido')
@php
    $moneda = request()->cookie('moneda', 'EUR');

    $simbolos = [
        'EUR' => '€',
        'USD' => '$',
        'GBP' => '£'
    ];
    $simboloMoneda = $simbolos[$moneda] ?? '€';
    $simboloDerecha = in_array($moneda, ['EUR']);
@endphp

    <section class="hero">
        <div class="container">
            <h1>Bienvenido a Kctta</h1>
            <p>Descubre nuestra colección de muebles de alta calidad</p>
            <a href="#categorias" class="btn btn-action btn-outline me-2">Explorar Categorías</a>
            <a href="#destacados" class="btn btn-action btn-light-outline">Ver Destacados</a>
        </div>
    </section>

    <section id="categorias" class="py-5">
        <div class="container">
            <h2 class="section-title">Explora Nuestras Categorías</h2>
            @if(count($categorias) > 0)
                <p class="text-center text-muted mb-4">
                    Estas son algunas de nuestras categorías, pero si deseas ver más pulsa el botón de ver más categorías.
                </p>
                <div class="row g-4 mb-4">
                    @foreach($categorias as $categoria)
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $categoria['nombre'] }}</h5>
                                    <p class="card-text text-muted">{{ $categoria['descripcion'] ?? '' }}</p>
                                    <a href="{{ route('categorias.show', $categoria['id']) }}" class="btn btn-primary w-100 mb-2">Ver productos</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center">
                    <a href="{{ route('categorias.index') }}" class="btn btn-action btn-outline">Ver más categorías →</a>
                </div>
            @else
                <div class="card p-4 text-center">
                    <p class="text-muted">No hay categorías disponibles en este momento.</p>
                </div>
            @endif
        </div>
    </section>

    <section id="destacados" class="py-5">
        <div class="container">
            <h2 class="section-title">Productos Destacados</h2>
            @if(count($productos) > 0)
                <div class="row g-4">
                    @foreach($productos as $producto)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100">
                                @if($producto['imagen_principal'] ?? null)
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
                                    <p class="mb-3"><strong>
                                        @if($simboloDerecha)
                                            {{ number_format($producto['precio'], 2) }} {{ $simboloMoneda }}
                                        @else
                                            {{ $simboloMoneda }} {{ number_format($producto['precio'], 2) }}
                                        @endif
                                    </strong></p>

                                    <div class="d-grid gap-2">
                                        @if(session()->has('api_token'))
                                            <form method="POST" action="{{ route('carrito.add', $producto['id']) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-primary w-100">Añadir al Carrito</button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary">Añadir al Carrito</a>
                                        @endif
                                        <a href="{{ route('productos.show', $producto['id']) }}" class="btn btn-outline-secondary">Ver detalles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card p-4 text-center">
                    <p class="text-muted">No hay productos destacados en este momento. ¡Visita nuestras categorías!</p>
                </div>
            @endif

            <div class="text-center mt-5 pt-4">
                <p class="h5 mb-3">¿Deseas ver más de nuestros muebles?</p>
                <p class="text-muted mb-4">Explora nuestro catálogo completo con todos los productos disponibles</p>
                <a href="{{ route('productos.index') }}" class="btn btn-action btn-outline">Explorar Muebles →</a>
            </div>
        </div>
    </section>

@endsection
