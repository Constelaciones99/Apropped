@extends('layouts.app')

@section('content')
<div class="container py-5">
  <h2 class="mb-4">🎯 Producto Detectado</h2>

  <div class="card shadow-sm p-4">
    <h4 class="fw-bold">{{ $producto->nombre }}</h4>
    <p class="text-muted">Descripción: {{ $producto->descripcion }}</p>
    <p class="text-muted">Precio: ${{ $producto->precio }}</p>
    <!-- Puedes mostrar más campos -->
    <div class="text-center">
        @if($imagenPrincipal)
            <img src="{{ asset('storage/' . $imagenPrincipal->ruta) }}" class="img-fluid rounded shadow-sm mb-4" style="max-height: 300px;" alt="Imagen principal del producto">
            <div class="mt-4">
                <form action="{{ route('ver.detalle.producto') }}" method="POST" >
                    @csrf
                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                    <button class="btn btn-success w-100">Ver Detalles</button>
                </form>
            </div>
        @else
            <p class="text-muted">Sin imagen principal disponible.</p>
        @endif
    </div>

    {{-- Mas resultados --}}
        @if($productosSimilares->count())
        <h4 class="mt-5">🔍 Productos Similares</h4>
        <div class="row g-4 mt-3">
            @foreach($productosSimilares as $item)
                <div class="col-md-3">
                    <div class="card shadow-sm h-100">
                        @if($item['imagen'])
                            <img src="{{ asset('storage/' . $item['imagen']->ruta) }}" class="card-img-top" alt="Imagen similar">

                        @endif
                            <div class="card-body">

                                <form action="{{ route('ver.detalle.producto') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $item['producto']->id }}">
                                    <button class="btn btn-outline-primary btn-sm w-100">Ver Detalle</button>
                                </form>

                                <h6 class="card-title">{{ $item['producto']->nombre }}</h6>
                                <p class="text-muted mb-1">{{ $item['similitud'] }}% de similitud</p>
                                <p class="small">{{ Str::limit($item['producto']->descripcion, 60) }}</p>

                            </div>
                        </div>
                </div>
            @endforeach
        </div>
        @endif

    {{--  --}}
  </div>
</div>
@endsection
