<div class="row">
    <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <img src="<?php echo e(asset('storage/' . ($producto->imagenPrincipal->ruta ?? 'default.jpg'))); ?>" class="card-img-top" alt="Producto">
            <div class="card-body">
                <h5 class="card-title"><?php echo e($producto->nombre); ?></h5>
                <p class="card-text text-muted"><?php echo e(Str::limit($producto->descripcion, 100)); ?></p>
                <p class="fw-bold">$<?php echo e($producto->precio); ?></p>
                <a href="<?php echo e(route('producto.detalle', $producto->id)); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye"></i> Ver más
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<div class="d-flex justify-content-center">
    <?php echo e($productos->links('vendor.pagination.bootstrap-5')); ?>

</div>
<?php /**PATH C:\xampp\htdocs\apropped\resources\views/partials/_productos.blade.php ENDPATH**/ ?>