@php $tema = request()->cookie('tema', 'claro'); @endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tienda de Muebles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>

<body class="tema-{{ $tema }}">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a href="{{ route('principal') }}" class="navbar-brand">Kctta</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('principal') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('categorias.index') }}">Categorías</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('productos.index') }}">Productos</a></li>
                    @if (session()->has('api_token'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('carrito.show') }}">Carrito</a></li>
                        <li class="nav-item">
                            <a href="{{ route('preferencias.edit') }}" class="nav-link">Preferencias</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('perfil.show') }}" class="nav-link">Mi perfil</a>
                        </li>
                        @if (in_array(session('usuario_rol'), ['Administrador', 'Gestor']))
                            <li class="nav-item"><a class="nav-link" href="{{ route('productos.create') }}">Crear
                                    Producto</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('categorias.create') }}">Crear
                                    Categoría</a></li>
                        @endif
                        @if (session('usuario_rol') === 'Administrador')
                            <li class="nav-item"><a class="nav-link" href="{{ route('administracion') }}">Admin</a></li>
                        @endif
                        <li class="nav-item"><span class="nav-link text-white">Bienvenido,
                                {{ session('usuario_nombre') }}</span></li>

                        <li class="nav-item">
                            <form action="{{ route('login.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-outline-light btn-sm">Cerrar sesión</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a href="{{ route('login') }}"
                                class="btn btn-action btn-outline ms-2">Iniciar sesión</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <main class="page-content">
        <div class="container mt-4">
            @yield('contenido')
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Kctta - Tienda de Muebles</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
