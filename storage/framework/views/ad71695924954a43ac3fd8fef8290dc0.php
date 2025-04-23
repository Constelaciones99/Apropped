<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h2 class="mb-4"><i class="fas fa-file-invoice"></i> Mis Boletas</h2>

    <?php if($boletas->isEmpty()): ?>
        <div class="alert alert-info">
            <i class="fa-solid fa-circle-info"></i> No tienes boletas registradas aún.
        </div>
    <?php else: ?>
    <table class="table table-striped table-hover shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Boleta</th>
                <th>Fecha</th>
                <th>Dirección</th>
                <th>Estado Pedido</th>
                <th>Ver Boleta</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $boletas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $boleta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>Boleta #<?php echo e($boleta->id); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($boleta->created_at)->format('d/m/Y')); ?></td>
                    <td><?php echo e($boleta->order->direccion ?? 'No registrada'); ?></td>
                    <td><?php echo e($boleta->order->estado ?? 'Sin estado'); ?></td>
                    <td>
                        <a href="<?php echo e(asset($boleta->numero)); ?>" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-file-pdf"></i> Ver PDF
                        </a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <!-- Links de paginación -->
    <div class="d-flex justify-content-center">
        <?php echo e($boletas->links('vendor.pagination.bootstrap-5')); ?>

    </div>
    <?php endif; ?>
</div>
<button class="btn btn-dark"><a href="<?php echo e(route('home')); ?>" class="text-white fw-bold"><i class="fa-solid fa-left-long"></i> Volver</a></button>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/cliente/boletas.blade.php ENDPATH**/ ?>