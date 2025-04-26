<div class="row g-4 ">
    <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden p-1">
                <img src="<?php echo e(asset('storage/' . ($producto->imagenPrincipal->ruta ?? 'default.jpg'))); ?>" class="card-img-top border" alt="Producto" style="height: 250px; object-fit: cover;">

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-truncate"><?php echo e($producto->nombre); ?></h5>

                    <p class="card-text text-muted small">
                        <?php echo e(Str::limit($producto->descripcion, 80)); ?>

                    </p>

                    <div class="mt-auto">
                        <p class="fw-bold fs-5 text-primary mb-3">$<?php echo e(number_format($producto->precio, 2)); ?></p>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?php echo e(route('producto.detalle', $producto->id)); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Ver más
                            </a>

                            <?php if(auth()->guard()->check()): ?>
                                <form action="<?php echo e(route('favorito.toggle')); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="producto_id" value="<?php echo e($producto->id); ?>">
                                    <button type="submit" class="btn btn-light btn-sm">
                                        <?php if(in_array($producto->id, $favoritos ?? [])): ?>
                                            <i class="fas fa-heart text-danger"></i> <!-- ❤️ Lleno -->
                                        <?php else: ?>
                                            <i class="far fa-heart text-danger"></i> <!-- 🤍 Vacío -->
                                        <?php endif; ?>
                                    </button>
                                </form>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="d-flex justify-content-center mt-4">
    <?php echo e($productos->links('vendor.pagination.bootstrap-5')); ?>

</div>
<?php /**PATH C:\xampp\htdocs\apropped\resources\views/partials/_productos.blade.php ENDPATH**/ ?>