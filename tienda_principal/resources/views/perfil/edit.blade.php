@extends('cabecera')

@section('contenido')
    <div class="container mt-4">
        <h2>Editar Perfil</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (!$usuario)
            <div class="alert alert-danger">No se pudo cargar el perfil.</div>
        @else
            <form action="{{ route('perfil.update') }}" method="POST" class="card shadow p-4 mt-3">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control"
                        value="{{ old('nombre', $usuario['nombre'] ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" id="apellidos" name="apellidos" class="form-control"
                        value="{{ old('apellidos', $usuario['apellidos'] ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="{{ $usuario['email'] ?? '' }}" disabled>
                    <small class="text-muted">El email no se puede modificar desde aquí.</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="{{ route('perfil.show') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        @endif
    </div>
@endsection
