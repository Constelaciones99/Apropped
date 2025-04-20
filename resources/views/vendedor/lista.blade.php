@foreach($productos as $producto)
    <div class="col-md-4 producto-item"
     data-id="{{ $producto->id }}"
     data-precio="{{ $producto->precio }}">
        <div class="card h-100">
            @php
                $imagenPrincipal = $imagenes->first(function ($img) use ($producto) {
                    return $img->producto_id == $producto->id && $img->es_principal == 1;
                });
            @endphp

            @if ($imagenPrincipal)
                <img src="{{ asset('storage/' . $imagenPrincipal->ruta) }}" class="card-img-top" alt="{{ $producto->nombre }}">
            @else
                <img src="{{ asset('images/default.jpg') }}" class="card-img-top" alt="Sin imagen">
            @endif

            <div class="card-body">
                <h5 class="card-title">{{ $producto->nombre }}</h5>
                <p class="card-text">Stock: {{ $producto->stock }}</p>
                <div class="d-grid gap-2">
                    <button class="btn btn-info btn-sm" onclick="verImagenes({{ $producto->id }})">
                        <i class="fas fa-images me-1"></i> Ver Imagen
                    </button>
                    <button class="btn btn-warning btn-sm" onclick="verDetalles('{{ $producto->descripcion }}')">
                        <i class="fas fa-info-circle me-1"></i> Ver Detalles
                    </button>
                    <button class="btn btn-success btn-sm" onclick="agregarAlCarrito({{ $producto->id }})">
                        <i class="fas fa-cart-plus me-1"></i> Añadir
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Paginación -->
<div class="mt-4 mb-5 d-flex justify-content-center">
    {{ $productos->links() }}
</div>

<script>
// Para manejar la paginación desde los enlaces generados por Laravel
    document.addEventListener('DOMContentLoaded', function() {
        // Volver a agregar los listeners a los enlaces de paginación
document.querySelectorAll('.pagination a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        let page = new URL(this.href).searchParams.get('page');
        filtrarProductos(page);
    });
});
    });


</script>
