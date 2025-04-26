@extends('layouts.app')

@section('content')
<div class="container pt-3">
    <a href="{{ route('home') }}" class="btn btn-dark text-white mb-3"><i class="fa-solid fa-left-long"></i> Volver</a>
    <h2 class="my-4">Tus Productos Favoritos</h2>

    @if ($productos->isEmpty())
        <div class="alert alert-warning">
            No tienes productos favoritos aún.
        </div>
    @else
        <div class="row g-4">
            @foreach ($productos as $producto)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden p-3">
                        <div class="d-flex align-items-center gap-3">

                            <!-- Imagen miniatura -->
                            <img src="{{ asset('storage/' . ($producto->imagenPrincipal->ruta ?? 'default.jpg')) }}"
                                class="rounded border"
                                alt="Producto"
                                style="width: 80px; height: 80px; object-fit: cover;">

                            <!-- Detalles -->
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1 text-truncate">{{ $producto->nombre }}</h5>
                                <p class="text-muted small mb-1">{{ Str::limit($producto->descripcion, 80) }}</p>
                                <p class="fw-bold text-primary mb-0">${{ number_format($producto->precio, 2) }}</p>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('producto.detalle', $producto->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('DeleteFav', $producto->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
