@extends('layouts.app')

@section('content')
<div class="container py-5 animate__animated animate__fadeIn">
    <div class="row g-4">
        <!-- Imagenes del producto -->
        <div class="col-md-6">
            <div class="border rounded-4 overflow-hidden shadow-sm position-relative">
                @if($producto->imagenes->count())
                    <div id="carouselProducto" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($producto->imagenes as $index => $imagen)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $imagen->ruta) }}" class="d-block w-100" style="object-fit: cover; height: 400px;" alt="Imagen del producto">
                                </div>
                            @endforeach
                        </div>

                        @if($producto->imagenes->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselProducto" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselProducto" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <img src="{{ asset('images/no-image.png') }}" class="img-fluid rounded" alt="Imagen por defecto">
                @endif

                <!-- Botón favoritos -->
                 <form action="{{ route('toggle.show.favorito', $producto->id) }}" method="POST" id="favorito-form">
                    @csrf
                    <button type="submit" class="btn p-0 border-0 bg-transparent" onclick="event.stopPropagation();">
                        <i class="fa{{ in_array($producto->id, Auth::user()->favoritos()) ? 's' : 'r' }} fa-heart text-danger fa-2x"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Detalles del producto -->
        <div class="col-md-6">
            <h1 class="mb-3">{{ $producto->nombre }}</h1>
            <hr>

            <h5 class="text-muted mb-2"><i class="fa-solid fa-align-left me-2"></i>Descripción:</h5>
            <p class="mb-4">{{ $producto->descripcion }}</p>

            <h5 class="text-muted mb-2"><i class="fa-solid fa-dollar-sign me-2"></i>Precio Unitario:</h5>
            <h3 class="text-success mb-4">S/ {{ number_format($producto->precio, 2) }}</h3>

            <!-- Botones acción -->
            <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" class="d-flex flex-column gap-3">
                @csrf
                <button type="submit" class="btn btn-success btn-lg shadow-sm">
                    <i class="fa-solid fa-cart-plus me-2"></i> Agregar al Carrito
                </button>
                <a href="{{ route('home') }}" class="btn btn-outline-dark btn-lg shadow-sm">
                    <i class="fa-solid fa-arrow-left me-2"></i> Seguir buscando
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
