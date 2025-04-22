<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h2 class="text-center mb-4">Generar Boleta</h2>

    <form id="formBoleta" action="<?php echo e(route('boleta.generar')); ?>" method="POST" target="_blank">
        <?php echo csrf_field(); ?>

        <div class="mb-3">
            <label for="cliente" class="form-label">Nombre del Cliente</label>
            <input type="text" name="nombre_cliente" id="cliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="vendedor" class="form-label">Seleccionar Vendedor</label>
            <select name="user_id" id="vendedor" class="form-select" required>
                <option value="">-- Seleccione --</option>
                <?php $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($usuario->rol == 'vendedor'): ?>
                        <option value="<?php echo e($usuario->id); ?>"><?php echo e($usuario->nombre); ?></option>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <h5 class="mt-4">Productos en el carrito</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $subtotal = 0; ?>
                <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $total = $producto['precio'] * $producto['cantidad']; $subtotal += $total; ?>
                    <tr>
                        <td><?php echo e($producto['id']); ?></td>
                        <td><?php echo e($producto['nombre']); ?></td>
                        <td>S/. <?php echo e(number_format($producto['precio'], 2)); ?></td>
                        <td><?php echo e($producto['cantidad']); ?></td>
                        <td>S/. <?php echo e(number_format($total, 2)); ?></td>
                    </tr>
                    <input type="hidden" name="productos[]" value='<?php echo json_encode($producto, 15, 512) ?>'>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <div class="text-end">
            <h5>Subtotal: S/. <?php echo e(number_format($subtotal, 2)); ?></h5>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="<?php echo e(route('vendedor.index')); ?>" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Aceptar y Generar Boleta</button>
        </div>
    </form>
</div>

    <script>
        document.getElementById('formBoleta').addEventListener('submit', function () {
            setTimeout(() => {
                window.location.href = '/vender';
            }, 1000); // redirige después de abrir el PDF
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/vendedor/boleta.blade.php ENDPATH**/ ?>