<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h1 class="text-center fw-bold mb-4">APROPPED</h1>

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


</div>

<!-- FOOTER FIJO DEL CARRITO -->
<!-- FOOTER CARRITO DESPLEGABLE -->
<div id="footerCarrito" class="position-fixed bottom-0 w-100 bg-white shadow-lg border-top transition-all" style="z-index: 1050; height: 60px;">
    <!-- Minimizado -->
    <div id="carritoMin" class="d-flex justify-content-between align-items-center px-4 py-2 cursor-pointer" onclick="expandirCarrito()">
        <div>
            <i class="fas fa-shopping-cart me-2"></i>
            <strong>Ver detalles de compra</strong>
        </div>
        <i class="fas fa-chevron-up"></i>
    </div>

    <!-- Expandido -->
    <div id="carritoDetalle" class="d-none p-3" style="max-height: 60vh; overflow-y: auto;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Carrito de compras</h5>
            <button class="btn btn-sm btn-light" onclick="minimizarCarrito()">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>

        <!-- Lista de productos -->
        <div id="listaCarrito"></div>

        <!-- Total y pagar -->
        <div class="mt-3 border-top pt-2 d-flex justify-content-between align-items-center">
            <strong>Subtotal: S/. <span id="subtotalCarrito">0.00</span></strong>
            <a href="<?php echo e(route('carrito.ver')); ?>" class="btn btn-success">
                <i class="fas fa-money-bill-wave me-1"></i> Pagar
            </a>
        </div>
    </div>
</div>



<?php $__env->stopSection(); ?>

<script>


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
        activarPaginacion(); // volver a activar los listeners
    })
    .catch(error => console.log(error));
}



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

      let carrito = {}; // Simulamos carrito local (o puedes usar datos reales con backend)

    function agregarAlCarrito(id) {
    const productoElem = document.querySelector(`[data-id="${id}"]`);
    const nombre = productoElem.querySelector('.card-title').textContent;
    const precio = parseFloat(productoElem.getAttribute('data-precio'));

    if (!carrito[id]) {
        carrito[id] = { nombre, precio, cantidad: 1 };
    } else {
        carrito[id].cantidad++;
    }
    renderizarCarrito();
}

function quitarDelCarrito(id) {
    delete carrito[id];
    renderizarCarrito();
}

function cambiarCantidad(id, delta) {
    if (carrito[id]) {
        carrito[id].cantidad += delta;
        if (carrito[id].cantidad <= 0) delete carrito[id];
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

</script>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/vendedor/index.blade.php ENDPATH**/ ?>