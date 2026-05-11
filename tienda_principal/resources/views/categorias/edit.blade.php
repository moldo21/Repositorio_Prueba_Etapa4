@extends('cabecera')

@section('contenido')

<div class="container mt-4">
    <h2 class="text-center mb-4">Editar categoría</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Corrige los siguientes errores:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categorias.update', $categoria['id']) }}" method="POST" class="card p-4 shadow">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la categoría</label>
            <input type="text" id="nombre" name="nombre" class="form-control"
                value="{{ old('nombre', $categoria['nombre']) }}" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="form-control" rows="4">{{ old('descripcion', $categoria['descripcion'] ?? '') }}</textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                Cancelar
            </a>

            <button type="submit" class="btn btn-primary">
                Guardar cambios
            </button>
        </div>
    </form>
</div>
@endsection
