<div class="row">
    @foreach ($productos as $producto)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <img src="{{ asset('storage/' . ($producto->imagenPrincipal->ruta ?? 'default.jpg')) }}" class="card-img-top" alt="Producto">
            <div class="card-body">
                <h5 class="card-title">{{ $producto->nombre }}</h5>
                <p class="card-text text-muted">{{ Str::limit($producto->descripcion, 100) }}</p>
                <p class="fw-bold">${{ $producto->precio }}</p>
                <a href="{{ route('producto.detalle', $producto->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye"></i> Ver más
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="d-flex justify-content-center">
    {{ $productos->links('vendor.pagination.bootstrap-5') }}
</div>
