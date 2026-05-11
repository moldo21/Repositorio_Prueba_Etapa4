@extends('cabecera')

@section('contenido')

    <div class="container mt-4">
        <h2 class="text-center mb-4">Elige una categoría</h2>

        @if (empty($categorias))
            <div class="text-center mt-4">
                <p>No hay categorías disponibles.</p>
            </div>
        @else
            <div class="row justify-content-center">
                @foreach ($categorias as $categoria)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $categoria['nombre'] }}</h5>
                                <p class="card-text text-muted">{{ $categoria['descripcion'] ?? '' }}</p>

                                <a href="{{ route('categorias.show', $categoria['id']) }}" class="btn btn-primary w-100 mb-2"
                                    title="Ver productos de {{ $categoria['nombre'] }}">
                                    Ver productos
                                </a>

                                @if (session()->has('api_token') && in_array(session('usuario_rol'), ['Administrador', 'Gestor']))
                                    <a href="{{ route('categorias.edit', $categoria['id']) }}"
                                        class="btn btn-warning w-100 mb-2"
                                        title="Editar categoría {{ $categoria['nombre'] }}">
                                        Editar
                                    </a>

                                    @if (session('usuario_rol') === 'Administrador')
                                    <form action="{{ route('categorias.destroy', $categoria['id']) }}" method="POST"
                                        onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                            Eliminar
                                        </button>
                                    </form>
                                    @endif
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <hr>
        <div class="text-center mt-3">
            <a href="{{ route('principal') }}" class="btn btn-outline-secondary me-2">
                ← Volver al inicio
            </a>
            <a href="{{ route('productos.index') }}" class="btn btn-primary">
                Ver todos los productos
            </a>
        </div>
    </div>
@endsection
