<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h2>Tu Carrito</h2>

    <?php if(session('carrito') && count(session('carrito')) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = session('carrito'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <img src="<?php echo e(asset('storage/' . $producto['imagen'])); ?>" alt="<?php echo e($producto['nombre']); ?>" class="img-thumbnail" style="width: 50px; height: 50px;">
                            <?php echo e($producto['nombre']); ?>

                        </td>

                        <td>

    <input type="number"
           name="cantidad"
           value="<?php echo e($producto['cantidad']); ?>"
           min="1"
           class="form-control form-control-sm cantidad-input"
           data-id="<?php echo e($id); ?>"
           data-precio="<?php echo e($producto['precio']); ?>"
           style="width: 70px;">
                            </td>
                        </td>
                        <td>S/. <?php echo e(number_format($producto['precio'], 2)); ?></td>
                        <td>S/. <span class="total-producto" data-id="<?php echo e($id); ?>"><?php echo e(number_format($producto['precio'] * $producto['cantidad'], 2)); ?></span></td>

                        <td>
                            <form action="<?php echo e(route('carrito.eliminar', $id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-danger btn-sm">Quitar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- Subtotal-->
        <?php
            $subtotal = array_sum(array_map(function($producto) {
                return $producto['precio'] * $producto['cantidad'];
            }, session('carrito')));
        ?>
        <div class="d-flex justify-content-end mb-3">
            <h4>Subtotal: S/. <span id="subtotal-carrito"><?php echo e(number_format($subtotal, 2)); ?></span></h4>
        </div>

        <div class="d-flex justify-content-between">
            <a href="<?php echo e(route('home')); ?>" class="btn btn-secondary">Seguir Comprando</a>

            <div>
                <form action="<?php echo e(route('carrito.vaciar')); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-outline-danger">Vaciar Carrito</button>
                </form>

                <a href="<?php echo e(route('carrito.ordenar')); ?>" class="btn btn-warning">Ordenar Ahora</a>
            </div>
        </div>

    <?php else: ?>
        <p>No tienes productos en tu carrito.</p>
        <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">Ir a la tienda</a>
    <?php endif; ?>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const inputsCantidad = document.querySelectorAll(".cantidad-input");

        inputsCantidad.forEach(input => {
            input.addEventListener("change", function () {
                const id = this.dataset.id;
                const precio = parseFloat(this.dataset.precio);
                const nuevaCantidad = parseInt(this.value) || 1;

                if (nuevaCantidad < 1) {
                    this.value = 1;
                    return;
                }

                // Actualiza el total por producto
                const totalProducto = document.querySelector(`.total-producto[data-id="${id}"]`);
                const nuevoTotal = (nuevaCantidad * precio).toFixed(2);
                totalProducto.textContent = nuevoTotal;

                // Recalcula el subtotal
                recalcularSubtotal();

                // También actualiza en sesión con fetch (AJAX)
                fetch(`/carrito/actualizar/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({ cantidad: nuevaCantidad })
                }).then(response => {
                    if (!response.ok) {
                        alert('Error actualizando carrito');
                    }
                });
            });
        });

        function recalcularSubtotal() {
            let subtotal = 0;
            document.querySelectorAll(".cantidad-input").forEach(input => {
                const precio = parseFloat(input.dataset.precio);
                const cantidad = parseInt(input.value) || 1;
                subtotal += precio * cantidad;
            });
            document.getElementById("subtotal-carrito").textContent = subtotal.toFixed(2);
        }
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/cliente/carrito.blade.php ENDPATH**/ ?>