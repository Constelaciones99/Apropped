<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h2>Resumen del Pedido</h2>

    <?php if(count($carrito) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $carrito; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($producto['nombre']); ?></td>
                        <td><?php echo e($producto['cantidad']); ?></td>
                        <td>S/. <?php echo e(number_format($producto['precio'], 2)); ?></td>
                        <td>S/. <?php echo e(number_format($producto['precio'] * $producto['cantidad'], 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            <h4>Subtotal: S/. <?php echo e(number_format($subtotal, 2)); ?></h4>
        </div>

        <div class="d-flex justify-content-between mt-4">

        <form action="<?php echo e(route('producto.ordenar.guardar', ['id' => 0])); ?>" method="POST" id="formBoleta">
                <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="nombre_cliente" class="form-label">Nombre</label>
                <input type="text" name="nombre_cliente" class="form-control"
                    value="<?php echo e(auth()->user()->nombre); ?>" required>
                    <input type="hidden" name="vendedor" value="Tienda APROPPED">
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control"
                    value="<?php echo e(auth()->user()->direccion); ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Comprar</button>
            <a href="<?php echo e(route('carrito.ver')); ?>" class="btn btn-secondary">Cancelar</a>
        </form>

        </div>
    <?php else: ?>
        <p>No hay productos en el carrito.</p>
    <?php endif; ?>
</div>

    <script>
        document.getElementById('formBoleta').addEventListener('submit', function () {
            setTimeout(() => {
                window.location.href = '/cliente';
            }, 1000); // redirige después de abrir el PDF
        });
        </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/cliente/ordenar.blade.php ENDPATH**/ ?>