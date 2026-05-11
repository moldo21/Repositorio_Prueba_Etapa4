@extends('cabecera')

@section('contenido')
    <h2 class="mb-4">Panel de Administración</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Estadísticas rápidas --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary text-center p-3">
                <h4>{{ count($usuarios) }}</h4>
                <p class="mb-0">Usuarios</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success text-center p-3">
                <h4>{{ count($muebles) }}</h4>
                <p class="mb-0">Productos</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning text-center p-3">
                <h4>{{ count($categorias) }}</h4>
                <p class="mb-0">Categorías</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary text-center p-3">
                <h4>{{ session('usuario_rol') }}</h4>
                <p class="mb-0">Tu rol</p>
            </div>
        </div>
    </div>

    {{-- Tabla de usuarios --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Usuarios registrados</strong>
        </div>
        <div class="card-body p-0">
            @if (empty($usuarios))
                <p class="text-muted p-3 mb-0">No se pudieron cargar los usuarios.</p>
            @else
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Registrado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $u)
                            <tr>
                                <td>{{ $u['id'] }}</td>
                                <td>{{ $u['nombre'] }} {{ $u['apellidos'] ?? '' }}</td>
                                <td>{{ $u['email'] }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $u['rol']['nombre'] ?? '—' }}</span>
                                </td>
                                <td>{{ $u['creado_en'] ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Tabla de productos --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Productos del catálogo</strong>
            <a href="{{ route('productos.create') }}" class="btn btn-sm btn-success">+ Nuevo producto</a>
        </div>
        <div class="card-body p-0">
            @if (empty($muebles))
                <p class="text-muted p-3 mb-0">No se pudieron cargar los productos.</p>
            @else
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($muebles as $m)
                            <tr>
                                <td>{{ $m['id'] }}</td>
                                <td>{{ $m['nombre'] }}</td>
                                <td>{{ $m['categoria']['nombre'] ?? '—' }}</td>
                                <td>{{ number_format($m['precio'], 2) }} €</td>
                                <td>{{ $m['stock'] }}</td>
                                <td>
                                    <a href="{{ route('productos.edit', $m['id']) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('productos.destroy', $m['id']) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('¿Eliminar producto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Tabla de categorías --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Categorías</strong>
            <a href="{{ route('categorias.create') }}" class="btn btn-sm btn-success">+ Nueva categoría</a>
        </div>
        <div class="card-body p-0">
            @if (empty($categorias))
                <p class="text-muted p-3 mb-0">No se pudieron cargar las categorías.</p>
            @else
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categorias as $cat)
                            <tr>
                                <td>{{ $cat['id'] }}</td>
                                <td>{{ $cat['nombre'] }}</td>
                                <td>{{ $cat['descripcion'] ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('categorias.edit', $cat['id']) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('categorias.destroy', $cat['id']) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('¿Eliminar categoría?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
