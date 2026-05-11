@extends('cabecera')

@section('contenido')
    <div class="container mt-4">
        <h2>Mi Perfil</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (!$usuario)
            <div class="alert alert-danger">No se pudo cargar el perfil. Inténtalo de nuevo.</div>
        @else
            <div class="card shadow p-4 mt-3">
                <div class="mb-3">
                    <strong>Nombre:</strong> {{ $usuario['nombre'] ?? '' }} {{ $usuario['apellidos'] ?? '' }}
                </div>
                <div class="mb-3">
                    <strong>Email:</strong> {{ $usuario['email'] ?? '' }}
                </div>
                <div class="mb-3">
                    <strong>Rol:</strong>
                    <span class="badge bg-secondary">{{ $usuario['rol']['nombre'] ?? session('usuario_rol') }}</span>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('perfil.edit') }}" class="btn btn-primary">Editar perfil</a>
                    <a href="{{ route('principal') }}" class="btn btn-outline-secondary">← Volver al inicio</a>
                </div>
            </div>
        @endif
    </div>
@endsection
