<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h1 class="text-center fw-bold mb-4">APROPPED</h1>
    <button class="btn btn-dark mb-5"><a href="<?php echo e(route('reporte')); ?>" class="text-white fw-bold"><i class="fa-solid fa-barcode"></i> Boletas</a></button>
    <!-- FILTROS -->
    <div class="row mb-4">
        <div class="col-md-4">
            <input type="text" id="buscador" class="form-control" placeholder="Buscar producto por nombre..." onkeyup="filtrarProductos()">
        </div>
        <div class="col-md-4">
            <select id="filtroCategoria" class="form-select" onchange="filtrarProductos()">
                <option value="">Todas las categorías</option>
                <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($categoria->id); ?>"><?php echo e($categoria->nombre); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-4">
            <select id="filtroPrecio" class="form-select" onchange="filtrarProductos()">
                <option value="">Ordenar por precio</option>
                <option value="menor">Menor a mayor</option>
                <option value="mayor">Mayor a menor</option>
            </select>
        </div>
    </div>

    <!-- LISTA DE PRODUCTOS -->
    <div id="listaProductos" class="row g-4">
        <?php echo $__env->make('vendedor.lista', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <!-- FOOTER CARRITO DESPLEGABLE -->
    <div id="footerCarrito" class="position-fixed bottom-0 w-75 bg-white shadow-lg border-top transition-all" style="z-index: 1050; height: 60px;">
        <!-- Minimizado -->
        <div id="carritoMin" class="d-flex justify-content-start align-items-center px-4 py-2 cursor-pointer" onclick="expandirCarrito()">
            <div>
                <i class="fas fa-shopping-cart me-2"></i>
                <strong>Ver detalles de compra</strong>
            </div>
            <i class="fas fa-chevron-up"></i>
        </div>

        <!-- Expandido -->
        <div id="carritoDetalle" class="d-none p-3 me-5" style="max-height: 60vh; overflow-y: auto;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Carrito de compras</h5>
                <button class="btn btn-sm btn-light" onclick="minimizarCarrito()">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>

            <div id="listaCarrito"></div>

            <div class="mt-3 border-top pt-2 d-flex justify-content-between align-items-center">
                <strong>Subtotal: S/. <span id="subtotalCarrito">0.00</span></strong>
                <button class="btn btn-success" onclick="enviarCarrito(event)">
                    <i class="fas fa-money-bill-wave me-1"></i> Pagar
                </button>
            </div>

            <button class="btn btn-danger btn-sm mt-2" onclick="vaciarCarrito()">
                <i class="fas fa-trash-alt me-1"></i> Vaciar Carrito
            </button>
        </div>
    </div>
</div>

<form id="formEnviarBoleta" action="<?php echo e(route('boleta.ver')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="productos" id="inputProductos">
</form>
<?php $__env->stopSection(); ?>

<script>
let carrito = {};

document.addEventListener('DOMContentLoaded', () => {
    cargarCarrito();
    setupDelegatedEvents();
    setupPaginacion();
});

function guardarCarrito() {
    localStorage.setItem('carrito', JSON.stringify(carrito));
}

function cargarCarrito() {
    const guardado = localStorage.getItem('carrito');
    if (guardado) {
        carrito = JSON.parse(guardado);

        // Asegurarse que cada producto tenga su id
        for (const id in carrito) {
            carrito[id].id = parseInt(id);
        }

        renderizarCarrito();
    }
}

function agregarAlCarrito(id) {
    const productoElem = document.querySelector(`[data-id="${id}"]`);
    const nombre = productoElem.querySelector('.card-title').textContent;
    const precio = parseFloat(productoElem.getAttribute('data-precio'));

    if (!carrito[id]) {
        carrito[id] = { id: parseInt(id),nombre, precio, cantidad: 1 };
    } else {
        carrito[id].cantidad++;
    }

    guardarCarrito();
    renderizarCarrito();
}

function quitarDelCarrito(id) {
    delete carrito[id];
    guardarCarrito();
    renderizarCarrito();
}

function cambiarCantidad(id, delta) {
    if (carrito[id]) {
        carrito[id].cantidad += delta;
        if (carrito[id].cantidad <= 0) delete carrito[id];
        guardarCarrito();
        renderizarCarrito();
    }
}

function renderizarCarrito() {
    const contenedor = document.getElementById('listaCarrito');
    contenedor.innerHTML = '';
    let subtotal = 0;

    Object.entries(carrito).forEach(([id, item]) => {
        const totalItem = item.precio * item.cantidad;
        subtotal += totalItem;

        contenedor.innerHTML += `
            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                <div>
                    <strong>${item.nombre}</strong><br>
                    <small>S/. ${item.precio.toFixed(2)} x ${item.cantidad} = <strong>S/. ${totalItem.toFixed(2)}</strong></small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick="cambiarCantidad(${id}, -1)">-</button>
                    <span>${item.cantidad}</span>
                    <button class="btn btn-sm btn-outline-secondary" onclick="cambiarCantidad(${id}, 1)">+</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="quitarDelCarrito(${id})">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        `;
    });

    document.getElementById('subtotalCarrito').textContent = subtotal.toFixed(2);
}

function expandirCarrito() {
    document.getElementById('carritoMin').classList.add('d-none');
    document.getElementById('carritoDetalle').classList.remove('d-none');
    document.getElementById('footerCarrito').style.height = '60vh';
}

function minimizarCarrito() {
    document.getElementById('carritoMin').classList.remove('d-none');
    document.getElementById('carritoDetalle').classList.add('d-none');
    document.getElementById('footerCarrito').style.height = '60px';
}

function vaciarCarrito() {
    carrito = {};
    guardarCarrito();
    renderizarCarrito();
}

function enviarCarrito(e) {
    e.preventDefault();
    if (Object.keys(carrito).length === 0) {
        alert('Tu carrito está vacío.');
        return;
    }
    const productosJSON = JSON.stringify(Object.values(carrito));
    console.log('Enviando productos:', productosJSON); // 👈 Agrega esto
    document.getElementById('inputProductos').value = productosJSON;
    document.getElementById('formEnviarBoleta').submit();
}

function setupDelegatedEvents() {
    document.addEventListener('click', function(e) {
        const addBtn = e.target.closest('.btn-agregar-carrito');
        if (addBtn) {
            const productoCard = addBtn.closest('[data-id]');
            if (productoCard) agregarAlCarrito(productoCard.dataset.id);
        }

        const verBtn = e.target.closest('.btn-ver-detalles');
        if (verBtn) {
            const id = verBtn.getAttribute('data-id');
            verDetalles(id);
        }

        const imgBtn = e.target.closest('.btn-ver-imagenes');
        if (imgBtn) {
            const id = imgBtn.getAttribute('data-id');
            verImagenes(id);
        }
    });
}

function filtrarProductos(page = 1) {
    const nombre = document.getElementById('buscador').value;
    const categoria = document.getElementById('filtroCategoria').value;
    const precio = document.getElementById('filtroPrecio').value;

    let url = `/vender-prod?page=${page}&nombre=${nombre}&categoria_id=${categoria}&precio=${precio}`;

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById('listaProductos').innerHTML = html;
        renderizarCarrito(); // actualizar visual
        setupPaginacion(); // reactivar paginación
    })
    .catch(err => console.error(err));
}

function setupPaginacion() {
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            let page = new URL(this.href).searchParams.get('page');
            filtrarProductos(page);
        });
    });
}

function verDetalles(id) {
    fetch(`/api/producto/${id}/detalles`)
        .then(res => res.json())
        .then(data => {
            const contenido = `
                <h5>${data.nombre}</h5>
                <p><strong>Precio:</strong> S/. ${data.precio}</p>
                <p><strong>Descripción:</strong> ${data.descripcion}</p>
                <p><strong>Categoría:</strong> ${data.categoria?.nombre ?? 'Sin categoría'}</p>
            `;
            document.getElementById('contenidoDetalle').innerHTML = contenido;
            new bootstrap.Modal(document.getElementById('modalDetalles')).show();
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
                imagenes.forEach((img, i) => {
                    carousel.innerHTML += `
                        <div class="carousel-item ${i === 0 ? 'active' : ''}">
                            <img src="/storage/${img.ruta}" class="d-block w-100" style="max-height: 400px; object-fit: contain;">
                        </div>`;
                });
            }

            new bootstrap.Modal(document.getElementById('modalImagenes')).show();
        });
}
</script>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/vendedor/index.blade.php ENDPATH**/ ?>