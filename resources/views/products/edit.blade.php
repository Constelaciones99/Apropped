@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Editar Producto</h2>

    <form action="{{ route('products.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $producto->nombre }}" required>
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4" required>{{ $producto->descripcion }}</textarea>
        </div>

        {{-- Precio --}}
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" name="precio" class="form-control" value="{{ $producto->precio }}" step="0.01" required>
        </div>

        {{-- Stock --}}
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="{{ $producto->stock }}" required>
        </div>

        {{-- Categoría --}}
        <div class="mb-3">
            <label for="categoria_id" class="form-label">Categoría</label>
            <select name="categoria_id" class="form-select" required>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ $producto->categoria_id == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Imagen principal actual --}}
        <div class="mb-3">
            <label class="form-label">Imagen principal actual</label><br>
            @php
                $mainImage = $producto->imagenes->where('es_principal', true)->first();
            @endphp
            @if ($mainImage)
                <img src="{{ asset('storage/' . $mainImage->ruta) }}" alt="Imagen principal" width="150">
            @else
                <p>No tiene imagen principal.</p>
            @endif
        </div>

        {{-- Cambiar imagen principal --}}
        <div class="mb-3">
            <label for="imagen_principal" class="form-label">Cambiar imagen principal</label>
            <input type="file" name="imagen_principal" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" multiple>
        </div>

        {{-- Agregar imágenes adicionales --}}
        <div class="mb-3">
    <label for="imagenes" class="form-label">Agregar nuevas imágenes</label>
    <input type="file" name="imagenes[]" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"  multiple>
        </div>

        <button type="submit" class="btn btn-warning mb-3">
    <i class="fas fa-sync-alt"></i> Actualizar
</button>
        <a href="{{ route('admin.index') }}" class="btn btn-secondary mb-3">
    <i class="fas fa-times"></i> Cancelar
</a>
    </form>
</div>
@endsection
