@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Crear Producto</h2>
    <!-- Tu formulario aquí -->
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ "POR FAVOR SELECCIONA OTRA IMAGEN" }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="form-group mb-3">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" maxlength="27" required>
        </div>

        <div class="form-group mb-3">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" class="form-control" id="descripcion" required></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="precio">Precio</label>
            <input type="number" name="precio" class="form-control" id="precio" required>
        </div>

        <div class="form-group mb-3">
            <label for="stock">Stock</label>
            <input type="number" name="stock" class="form-control" id="stock" required>
        </div>

        <div class="form-group mb-3">
            <label for="categoria_id">Categoría</label>
            <select name="categoria_id" class="form-control" id="categoria_id" required>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="imagenes" class="form-label">Imágenes del producto</label>
            <input type="file" name="imagenes[]" id="imagenes" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" multiple>
        </div>

        <div class="d-flex justify-content-between mt-4">
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Cancelar
    </a>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Agregar Producto
    </button>
</div>
    </form>
</div>
@endsection
