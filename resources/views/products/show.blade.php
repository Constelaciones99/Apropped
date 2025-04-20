@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">← Volver a la lista</a>

    <div class="card shadow">
        <div class="row g-0">

            {{-- @if($producto->imagenes->isNotEmpty())

                <h4 class="mt-4">Galería de imágenes:</h4>
                <div class="row">
                    @foreach ($producto->imagenes as $imagen)
                        <div class="col-md-3 mb-3">
                            <img src="{{ asset('storage/' . $imagen->ruta) }}" class="img-fluid rounded border" alt="Imagen del producto">
                            @if ($imagen->es_principal)
                                <span class="badge bg-primary mt-1">Principal</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif --}}

    <div class="col-md-7">
        <div class="card-body">

                <h1>{{ $producto->nombre }}</h1>
                <p>{{ $producto->descripcion }}</p>
                <p><strong>Precio:</strong> ${{ $producto->precio }}</p>
                <p><strong>Stock:</strong> {{ $producto->stock }}</p>
                <p><strong>Categoría:</strong> {{ $producto->categoria->nombre }}</p>
                <!-- Galería de imágenes -->
                <h4>Galería de Imágenes</h4>
                <div class="row">
    @foreach ($producto->imagenes as $imagen)
        <div class="col-md-3 mb-3">
            <div class="card">
                <img src="{{ asset('storage/' . $imagen->ruta) }}" class="card-img-top" alt="Imagen del producto">

                <div class="card-body text-center">
                    @if (!$imagen->es_principal)
                        <form action="{{ route('products.setPrincipal', [$producto->id, $imagen->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-outline-success mb-1">Hacer principal</button>
                        </form>

                        <form action="{{ route('products.deleteImage', [$producto->id, $imagen->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta imagen?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                        </form>
                    @else
                        <span class="badge bg-primary">Principal</span>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

               <div class="d-flex align-items-center mb-3" style="gap: 10px;">
    <a href="{{ route('products.edit', $producto->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Editar Producto
    </a>

    <form action="{{ route('products.destroy', $producto->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i> Eliminar Producto
        </button>
    </form>
</div>


        </div>
    </div>
        </div>
    </div>
</div>
<hr>
<h5 class="mt-4">Agregar imagen al producto</h5>
<form action="{{ route('products.uploadImage', $producto->id) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
    @csrf
    <label class="btn btn-secondary">
        <i class="fas fa-upload"></i> Elegir archivo
        <input type="file" name="imagen" hidden id="imagenInput">
    </label>

    <button type="submit" class="btn btn-success" id="submitBtn" disabled>
        <i class="fas fa-cloud-upload-alt"></i> Subir imagen
    </button>
</form>



<script>
    // Espera a que el DOM esté listo
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('imagenInput');
        const submitBtn = document.getElementById('submitBtn');

        fileInput.addEventListener('change', function () {
            // Si se selecciona un archivo, activar el botón
            submitBtn.disabled = !fileInput.files.length;
        });
    });
</script>
@endsection
