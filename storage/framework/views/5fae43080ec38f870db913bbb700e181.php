<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2 class="mb-4">Reporte de Boletas</h2>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nombre Cliente</th>
                <th>Boleta</th>
                <th>Fecha</th>
                <th>Ver</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $boletas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $boleta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $cliente = $boleta->order->nombre_cliente ?? 'Sin nombre';
                    // Normalizar nombre del archivo
                    $archivoPDF = Str::endsWith($boleta->numero, '.pdf')
                        ? $boleta->numero
                        : 'boleta_' . $boleta->id . '.pdf';

                    $rutaPDF = asset( $archivoPDF);
                ?>
                <tr>
                    <td><?php echo e($cliente); ?></td>
                    <td><?php echo e($archivoPDF); ?></td>
                    <td><?php echo e($boleta->created_at->format('d/m/Y')); ?></td>
                    <td>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBoleta<?php echo e($boleta->id); ?>">
                            Ver
                        </button>
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="modalBoleta<?php echo e($boleta->id); ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo e($boleta->id); ?>" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel<?php echo e($boleta->id); ?>">Boleta N° <?php echo e($boleta->id); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <iframe src="<?php echo e($rutaPDF); ?>" width="100%" height="600px" frameborder="0"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/products/reporte.blade.php ENDPATH**/ ?>