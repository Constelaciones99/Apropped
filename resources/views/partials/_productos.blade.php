<div class="row g-4 ">
    @foreach ($productos as $producto)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden p-1">
                <img src="{{ asset('storage/' . ($producto->imagenPrincipal->ruta ?? 'default.jpg')) }}" class="card-img-top border" alt="Producto" style="height: 250px; object-fit: cover;">

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-truncate">{{ $producto->nombre }}</h5>

                    <p class="card-text text-muted small">
                        {{ Str::limit($producto->descripcion, 80) }}
                    </p>

                    <div class="mt-auto">
                        <p class="fw-bold fs-5 text-primary mb-3">${{ number_format($producto->precio, 2) }}</p>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('producto.detalle', $producto->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Ver más
                            </a>

                            @auth
                                <form action="{{ route('favorito.toggle') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                    <button type="submit" class="btn btn-light btn-sm">
                                        @if(in_array($producto->id, $favoritos ?? []))
                                            <i class="fas fa-heart text-danger"></i> <!-- ❤️ Lleno -->
                                        @else
                                            <i class="far fa-heart text-danger"></i> <!-- 🤍 Vacío -->
                                        @endif
                                    </button>
                                </form>
                            @endauth

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $productos->links('vendor.pagination.bootstrap-5') }}
</div>
