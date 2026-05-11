@extends('cabecera')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/carrito.css') }}">
@endpush

@section('contenido')
    @php
        $moneda = Cookie::get('moneda', 'EUR');
        $simbolo = $moneda === 'USD' ? '$' : ($moneda === 'GBP' ? '£' : '€');
        $simboloDerecha = $moneda === 'EUR';
    @endphp

    <div class="carrito-container">
        <h2>Tu carrito</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($items->isEmpty())
            <p>No tienes productos en el carrito.</p>
            <a href="{{ route('productos.index') }}" class="btn btn-action btn-comprar">Ver productos</a>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->nombre_producto }}</td>
                            <td>
                                @if ($simboloDerecha)
                                    {{ number_format($item->precio_unitario, 2) }} {{ $simbolo }}
                                @else
                                    {{ $simbolo }} {{ number_format($item->precio_unitario, 2) }}
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <form method="POST" action="{{ route('carrito.disminuir', $item->id) }}" class="me-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary">-</button>
                                    </form>
                                    <span class="px-2">{{ $item->cantidad }}</span>
                                    <form method="POST" action="{{ route('carrito.aumentar', $item->id) }}" class="ms-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">+</button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                @if ($simboloDerecha)
                                    {{ number_format($item->cantidad * $item->precio_unitario, 2) }} {{ $simbolo }}
                                @else
                                    {{ $simbolo }} {{ number_format($item->cantidad * $item->precio_unitario, 2) }}
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('carrito.remove', $item->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="resumen-total">
                <h4>Subtotal:
                    @if ($simboloDerecha)
                        {{ number_format($subtotal, 2) }} {{ $simbolo }}
                    @else
                        {{ $simbolo }} {{ number_format($subtotal, 2) }}
                    @endif
                </h4>
                <h4>Impuestos (10%):
                    @if ($simboloDerecha)
                        {{ number_format($impuestos, 2) }} {{ $simbolo }}
                    @else
                        {{ $simbolo }} {{ number_format($impuestos, 2) }}
                    @endif
                </h4>
                <h4>Total:
                    @if ($simboloDerecha)
                        {{ number_format($total, 2) }} {{ $simbolo }}
                    @else
                        {{ $simbolo }} {{ number_format($total, 2) }}
                    @endif
                </h4>
            </div>

            <div class="mt-3 d-flex gap-2">
                <form method="POST" action="{{ route('carrito.comprar') }}">
                    @csrf
                    <button type="submit" class="btn btn-action btn-comprar">Comprar</button>
                </form>

                <form method="POST" action="{{ route('carrito.clear') }}">
                    @csrf
                    <button type="submit" class="btn btn-action btn-vaciar">Vaciar carrito</button>
                </form>

                <a href="{{ route('productos.index') }}" class="btn btn-action btn-vaciar">Seguir comprando</a>
            </div>
        @endif
    </div>
@endsection
