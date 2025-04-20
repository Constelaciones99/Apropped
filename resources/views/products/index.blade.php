@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Lista de Productos</h1>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Agregar nuevo producto
    </a>
    <form action="{{ route('products.index') }}" method="GET" class="mb-4">
    <div class="row align-items-end">
        <!-- Ordenar por -->
        <div class="col-md-4">
            <label for="orden">Ordenar por:</label>
            <select name="orden" id="orden" class="form-control">
                <option value="">-- Selecciona --</option>
                <option value="aleatorio" {{ request('orden') == 'aleatorio' ? 'selected' : '' }}>Aleatorio</option>
                <option value="recientes" {{ request('orden') == 'recientes' ? 'selected' : '' }}>Más recientes</option>
            </select>
        </div>

        <!-- Filtrar por categoría -->
        <div class="col-md-4">
            <label for="categoria">Categoría:</label>
            <select name="categoria" id="categoria" class="form-control">
                <option value="">-- Todas --</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Botón de aplicar -->
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Aplicar filtros
            </button>
        </div>
    </div>
</form>

    <div class="d-flex justify-content-center mt-4">
    {{ $productos->links('vendor.pagination.bootstrap-4') }}

</div>
    @if($productos->isEmpty())
        <p>No hay productos disponibles.</p>
    @else
        <div class="row">
            <!-- index.blade.php -->
@foreach($productos as $producto)
    <div class="col-md-4 mb-4">
        <div class="card h-100 text-center pt-2">
            @php
    $imgPrincipal = $producto->imagenes->where('es_principal', true)->first();
@endphp

@if($imgPrincipal)
    <img src="{{ asset('storage/' . $imgPrincipal->ruta) }}"
         class="card-img-top m-auto"
         alt="Imagen principal del producto"
         style="cursor:pointer; max-height:20rem; width:auto"
         data-bs-toggle="modal"
         data-bs-target="#modalProducto{{ $producto->id }}">
@endif

            <div class="card-body">
                    <h5 class="card-title">{{ $producto->nombre }}</h5>
                    <p class="card-text">{{ Str::limit($producto->descripcion, 80) }}</p>

                    <!-- Mostrar Precio -->
                    <p><strong>Precio:</strong> ${{ number_format($producto->precio, 2) }}</p>

                    <!-- Mostrar Categoría -->
                    <p><strong>Categoría:</strong> {{ $producto->categoria->nombre ?? 'Sin categoría' }}</p>

                    <p><strong>Stock:</strong> {{ $producto->stock }}</p>

                    <a href="{{ route('products.show', $producto->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i> Ver más
                    </a>
                </div>
        </div>
    </div>

    <!-- Modal con slider -->
    <div class="modal fade" id="modalProducto{{ $producto->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="carouselProducto{{ $producto->id }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($producto->imagenes as $index => $imagen)
                                <div class="carousel-item @if($index == 0) active @endif">
                                    <img src="{{ asset('storage/' . $imagen->ruta) }}" class="d-block w-100" alt="...">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselProducto{{ $producto->id }}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselProducto{{ $producto->id }}" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach


        </div>
    @endif
</div>


@endsection
