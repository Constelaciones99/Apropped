<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    
    <h2 class="mb-4"><i class="fa-solid fa-clipboard-list"></i> Reporte de Boletas</h2>

    <!-- Buscador con íconos FontAwesome 😍 resultados-->
    <form action="<?php echo e(route('boletas.buscar')); ?>" method="GET" class="d-flex mb-4 gap-2 align-items-center">
        <input type="text" name="query" class="form-control" placeholder="🔎 Buscar por boleta, cliente u orden..." value="<?php echo e(request('query')); ?>">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-magnifying-glass"></i> Buscar
        </button>
    </form>

    <?php if(isset($busqueda)): ?>
        <div class="mb-3">
            <span class="text-muted">
                <i class="fa-solid fa-filter-circle-xmark"></i> Resultados para: <strong>"<?php echo e($busqueda); ?>"</strong>
            </span>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover shadow-sm rounded-3 overflow-hidden">
        <thead class="table-dark">
            <tr>
                <th><i class="fa-solid fa-user"></i> Nombre Cliente</th>
                <th><i class="fa-solid fa-file-invoice"></i> Boleta</th>
                <th><i class="fa-solid fa-calendar-days"></i> Fecha</th>
                <th><i class="fa-solid fa-eye"></i> Ver</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $boletas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $boleta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $cliente = $boleta->nombre_cliente ?? ($boleta->order->nombre_cliente ?? 'Sin nombre');
                    $archivoPDF = Str::endsWith($boleta->id, '.pdf')
                        ? $boleta->numero
                        : 'Boleta_' . $boleta->id;
                    $rutaPDF = asset($boleta->numero);
                ?>
                <tr>
                    <td><?php echo e($cliente); ?></td>
                    <td><?php echo e($archivoPDF); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($boleta->created_at)->format('d/m/Y')); ?></td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalBoleta<?php echo e($boleta->id); ?>">
                            <i class="fa-solid fa-file-pdf"></i> PDF
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        <i class="fa-solid fa-circle-info"></i> No se encontraron boletas para esta búsqueda.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
    <?php echo e($boletas->appends(['query' => request('query')])->links('vendor.pagination.bootstrap-5')); ?>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/products/reporte.blade.php ENDPATH**/ ?>