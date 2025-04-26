<?php $__env->startSection('content'); ?>
<div class="container py-5">
  <h2 class="mb-4">🎯 Producto Detectado</h2>

  <div class="card shadow-sm p-4">
    <h4 class="fw-bold"><?php echo e($producto->nombre); ?></h4>
    <p class="text-muted">Descripción: <?php echo e($producto->descripcion); ?></p>
    <p class="text-muted">Precio: $<?php echo e($producto->precio); ?></p>
    <!-- Puedes mostrar más campos -->
    <div class="text-center">
        <?php if($imagenPrincipal): ?>
            <img src="<?php echo e(asset('storage/' . $imagenPrincipal->ruta)); ?>" class="img-fluid rounded shadow-sm mb-4" style="max-height: 300px;" alt="Imagen principal del producto">
            <div class="mt-4">
                <form action="<?php echo e(route('ver.detalle.producto')); ?>" method="POST" >
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="producto_id" value="<?php echo e($producto->id); ?>">
                    <button class="btn btn-success w-100">Ver Detalles</button>
                </form>
            </div>
        <?php else: ?>
            <p class="text-muted">Sin imagen principal disponible.</p>
        <?php endif; ?>
    </div>

    
        <?php if($productosSimilares->count()): ?>
        <h4 class="mt-5">🔍 Productos Similares</h4>
        <div class="row g-4 mt-3">
            <?php $__currentLoopData = $productosSimilares; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-3">
                    <div class="card shadow-sm h-100">
                        <?php if($item['imagen']): ?>
                            <img src="<?php echo e(asset('storage/' . $item['imagen']->ruta)); ?>" class="card-img-top" alt="Imagen similar">

                        <?php endif; ?>
                            <div class="card-body">

                                <form action="<?php echo e(route('ver.detalle.producto')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="producto_id" value="<?php echo e($item['producto']->id); ?>">
                                    <button class="btn btn-outline-primary btn-sm w-100">Ver Detalle</button>
                                </form>

                                <h6 class="card-title"><?php echo e($item['producto']->nombre); ?></h6>
                                <p class="text-muted mb-1"><?php echo e($item['similitud']); ?>% de similitud</p>
                                <p class="small"><?php echo e(Str::limit($item['producto']->descripcion, 60)); ?></p>

                            </div>
                        </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

    
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/prediccion/producto.blade.php ENDPATH**/ ?>