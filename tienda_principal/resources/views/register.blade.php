<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Kctta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>
    <div class="register-container">
        <div class="register-card">
            <div class="brand-link">
                <a href="{{ route('principal') }}" class="brand-name">Kctta</a>
            </div>

            <h1 class="register-title">Crear cuenta</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                           value="{{ old('nombre') }}" placeholder="Introduce tu nombre" required>
                </div>

                <div class="form-group">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control"
                           value="{{ old('apellidos') }}" placeholder="Introduce tus Apellidos" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email') }}" placeholder="ej@gmail.com" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control"
                           placeholder="Escribe una contraseña" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control" placeholder="Repite la contraseña" required>
                </div>

                <button type="submit" class="btn btn-register">Registrarme</button>

                <div class="login-link">
                    <span class="text-muted">¿Ya tienes cuenta?</span>
                    <a href="{{ route('login') }}">Inicia sesión aquí</a>
                </div>
            </form>

            <div class="back-link">
                <a href="{{ route('principal') }}">← Volver a la página principal</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
