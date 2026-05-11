@extends('cabecera')

@section('contenido')

    <h2 class="mb-4">Editar Producto: {{ $producto['nombre'] }}</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('productos.update', $producto['id']) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nombre *</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $producto['nombre']) }}"
                maxlength="80" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción *</label>
            <textarea name="descripcion" class="form-control" rows="3" maxlength="255" required>{{ old('descripcion', $producto['descripcion']) }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Precio (€) *</label>
                <input type="number" name="precio" class="form-control" step="0.01" min="0"
                    value="{{ old('precio', $producto['precio']) }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Stock *</label>
                <input type="number" name="stock" class="form-control" min="0"
                    value="{{ old('stock', $producto['stock']) }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Color principal *</label>
                <input type="text" name="color_principal" class="form-control" maxlength="50"
                    value="{{ old('color_principal', $producto['color_principal']) }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Materiales *</label>
            <input type="text" name="materiales" class="form-control" maxlength="255"
                value="{{ old('materiales', $producto['materiales']) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Dimensiones *</label>
            <input type="text" name="dimensiones" class="form-control" maxlength="255"
                value="{{ old('dimensiones', $producto['dimensiones']) }}" required>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="destacado" value="1"
                {{ old('destacado', $producto['destacado'] ?? false) ? 'checked' : '' }}>
            <label class="form-check-label"><i class="bi bi-star-fill"></i> Producto destacado</label>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoría *</label>
            <select name="categoria_id" class="form-select" required>
                <option value="">Seleccione...</option>
                @foreach ($categorias as $cat)
                    <option value="{{ $cat['id'] }}"
                        {{ old('categoria_id', $producto['categoria_id'] ?? ($producto['categoria']['id'] ?? '')) == $cat['id'] ? 'selected' : '' }}>
                        {{ $cat['nombre'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen principal (dejar vacío para mantener la actual)</label>
            @if ($producto['imagen_principal'] ?? null)
                <div class="mb-2">
                    <img src="{{ $producto['imagen_url'] ?? asset('imagenes/' . $producto['imagen_principal']) }}" style="max-height: 120px;" class="rounded">
                </div>
            @endif
            <input type="file" name="imagen" class="form-control" accept="image/jpeg,image/png,image/jpg">
            <small class="text-muted">Formatos: JPG, JPEG, PNG. Máximo 2MB</small>
        </div>

        <button type="submit" class="btn btn-success">✓ Guardar cambios</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>

    <hr class="my-5">

    <h3>Galería de Imágenes</h3>

    @php
        $galerias = collect($producto['galerias'] ?? [])->sortBy('orden')->values()->all();
    @endphp
    <div class="row">
        @forelse($galerias as $galeria)
            <div class="col-md-3 text-center mb-4">
                <img src="{{ $galeria['url'] ?? asset('imagenes/' . $galeria['ruta']) }}"
                    class="img-fluid rounded shadow"
                    style="max-height: 200px; width: 100%; object-fit: cover;">

                <div class="mt-2">

                    @if($galeria['es_principal'] ?? false)
                        <span class="badge bg-success mb-2">Principal</span>
                    @endif

                    <div class="btn-group d-block" role="group">

                        @if(!($galeria['es_principal'] ?? false))
                            <form action="{{ route('productos.galeria.principal', [$producto['id'], $galeria['id']]) }}"
                                method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-primary" title="Marcar como principal">
                                    <i class="bi bi-star-fill"></i> Principal
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('productos.galeria.destroy', [$producto['id'], $galeria['id']]) }}"
                            method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar esta imagen?');"
                                title="Eliminar imagen">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No hay imágenes en la galería.</p>
        @endforelse
    </div>

    <hr>

    <h4>Agregar nuevas imágenes</h4>

    <form action="{{ route('productos.galeria.store', $producto['id']) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <input type="file" name="imagenes[]" multiple accept="image/jpeg,image/png,image/jpg"
                class="form-control">
            <small class="text-muted">Puedes seleccionar múltiples imágenes. Formatos: JPG, JPEG, PNG. Máximo 2MB cada una.</small>
        </div>
        <button class="btn btn-primary">Subir imágenes</button>
    </form>
@endsection
