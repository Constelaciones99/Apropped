@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6 border">
            @if($producto->imagenes->count())
                <div id="carouselProducto" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" style="overflow: hidden;">
                        @foreach($producto->imagenes as $index => $imagen)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $imagen->ruta) }}" class="d-block w-100 h-100 object-fit-cover" alt="Imagen del producto">
                            </div>
                        @endforeach
                    </div>

                    @if($producto->imagenes->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselProducto" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselProducto" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    @endif
                </div>
            @else
                <img src="{{ asset('images/default.jpg') }}" class="img-fluid" alt="Imagen por defecto">
            @endif
        </div>

        <div class="col-md-6">
            <h2>{{ $producto->nombre }}</h2>
            <p>{{ $producto->descripcion }}</p>
            <h4>S/. {{ number_format($producto->precio, 2) }}</h4>

            <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Agregar al Carrito</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const precioUnitario = {{ $producto->precio }};
        const cantidadInput = document.getElementById("cantidad");
        const totalPrecio = document.getElementById("totalPrecio");

        cantidadInput.addEventListener("input", function () {
            let cantidad = parseInt(cantidadInput.value);
            if (isNaN(cantidad) || cantidad < 1) cantidad = 1;
            totalPrecio.textContent = "S/" + (precioUnitario * cantidad).toFixed(2);
        });
    });
</script>
@endsection
