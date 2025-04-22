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

            <img src="{{ $imagenPrincipal ? asset('storage/' . $imagenPrincipal->ruta) : asset('images/default.jpg') }}"
                 class="card-img-top pt-2"
                 alt="{{ $producto->nombre }}">

            <div class="card-body">
                <h5 class="card-title">{{ $producto->nombre }}</h5>
                <p class="card-text">Stock: {{ $producto->stock." - Precio: S/. ".$producto->precio }}</p>
                <div class="d-grid gap-2">
                    <button class="btn btn-info btn-sm btn-ver-imagenes" data-id="{{ $producto->id }}">
                        <i class="fas fa-images me-1"></i> Ver Imagen
                    </button>
                    <button class="btn btn-warning btn-sm btn-ver-detalles" data-id="{{ $producto->id }}">
                        <i class="fas fa-info-circle me-1"></i> Ver Detalles
                    </button>
                    <button class="btn btn-success btn-sm btn-agregar-carrito">
                        <i class="fas fa-cart-plus me-1"></i> Añadir
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach


<!-- Modal de Detalles del Producto true-->
<div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="tituloDetalle">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloDetalle">Detalles del Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="contenidoDetalle">
        Cargando...
      </div>
    </div>
  </div>
</div>

<!-- Modal Imagenes (Slider) -->
<div class="modal fade" id="modalImagenes" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Imágenes del producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="carouselImagenes" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner" id="carouselInner">
            <!-- Las imágenes se llenan desde JS -->
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselImagenes" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselImagenes" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>




<!-- Paginación -->
<div class="mt-4 mb-5 d-flex justify-content-center">
    {{ $productos->links() }}
</div>

<script>

// Para manejar la paginación desde los enlaces generados por Laravel
function cargarCarrito() {
    const guardado = localStorage.getItem('carrito');
    if (guardado) {
        carrito = JSON.parse(guardado);
        renderizarCarrito();
    }
}

 function filtrarProductos(page = 1) {
        let nombre = document.getElementById('buscador').value;
    let categoria = document.getElementById('filtroCategoria').value;
    let precio = document.getElementById('filtroPrecio').value;

    let url = `/vender-prod?page=${page}&nombre=${nombre}&categoria_id=${categoria}&precio=${precio}`;

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // 👈 este es la clave
        }
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('listaProductos').innerHTML = data;
        renderizarCarrito();
         // volver a activar los listeners
    })
    .catch(error => console.log(error));

    function activarPaginacion(){
    document.addEventListener('DOMContentLoaded', function(){
        cargarCarrito();
    activarPaginacion();
        // Volver a agregar los listeners a los enlaces de paginación
                document.querySelectorAll('.pagination a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        let page = new URL(this.href).searchParams.get('page');
                        filtrarProductos(page);
                    });
                });

    })
        }

}

function verDetalles(id) {
    fetch(`/api/producto/${id}/detalles`)
        .then(response => {
            if (!response.ok) throw new Error("Error al obtener detalles");
            return response.json();
        })
        .then(data => {
            let contenido = `
                <h5>${data.nombre}</h5>
                <p><strong>Precio:</strong> S/. ${data.precio}</p>
                <p><strong>Descripción:</strong> ${data.descripcion}</p>
                <p><strong>Categoría:</strong> ${data.categoria?.nombre ?? 'Sin categoría'}</p>
            `;
            document.getElementById('contenidoDetalle').innerHTML = contenido;
            new bootstrap.Modal(document.getElementById('modalDetalles')).show();
        })
        .catch(error => {
            document.getElementById('contenidoDetalle').innerHTML = '<p class="text-danger">Error cargando detalles.</p>';
            console.error(error);
        });
}


function verImagenes(productoId) {
    fetch(`/api/producto/${productoId}/imagenes`)
        .then(res => res.json())
        .then(imagenes => {
            const carousel = document.getElementById('carouselInner');
            carousel.innerHTML = '';

            if (imagenes.length === 0) {
                carousel.innerHTML = `
                    <div class="carousel-item active">
                        <div class="text-center py-5">Sin imágenes disponibles</div>
                    </div>`;
            } else {
                imagenes.forEach((img, index) => {
                    carousel.innerHTML += `
                        <div class="carousel-item ${index === 0 ? 'active' : ''}">
                            <img src="/storage/${img.ruta}" class="d-block w-100" style="max-height: 400px; object-fit: contain;">
                        </div>`;
                });
            }

            new bootstrap.Modal(document.getElementById('modalImagenes')).show();
        });
}



</script>
