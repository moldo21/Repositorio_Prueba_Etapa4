<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Kctta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="brand-link">
                <a href="{{ route('principal') }}" class="brand-name">Kctta</a>
            </div>

            <h1 class="login-title">Iniciar sesión</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" name="email" id="email" class="form-control"
                       value="{{ old('email') }}" placeholder="ejemplo@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control"
                       placeholder="Introduce tu contraseña" required>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="recuerdame" value="1" id="recuerdame" class="form-check-input">
                <label for="recuerdame" class="form-check-label">Recordarme</label>
            </div>

            <button type="submit" class="btn btn-login">Iniciar sesión</button>

            <div class="register-link">
                <span class="text-muted">¿No tienes cuenta?</span>
                <a href="{{ route('register.show') }}">Regístrate aquí</a>
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
