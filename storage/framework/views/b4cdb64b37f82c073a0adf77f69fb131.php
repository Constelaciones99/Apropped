<?php $__env->startSection('content'); ?>
<div class="container pt-3">
    <a href="<?php echo e(route('home')); ?>" class="btn btn-dark text-white mb-3"><i class="fa-solid fa-left-long"></i> Volver</a>
    <h2 class="my-4">Tus Productos Favoritos</h2>

    <?php if($productos->isEmpty()): ?>
        <div class="alert alert-warning">
            No tienes productos favoritos aún.
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden p-3">
                        <div class="d-flex align-items-center gap-3">

                            <!-- Imagen miniatura -->
                            <img src="<?php echo e(asset('storage/' . ($producto->imagenPrincipal->ruta ?? 'default.jpg'))); ?>"
                                class="rounded border"
                                alt="Producto"
                                style="width: 80px; height: 80px; object-fit: cover;">

                            <!-- Detalles -->
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1 text-truncate"><?php echo e($producto->nombre); ?></h5>
                                <p class="text-muted small mb-1"><?php echo e(Str::limit($producto->descripcion, 80)); ?></p>
                                <p class="fw-bold text-primary mb-0">$<?php echo e(number_format($producto->precio, 2)); ?></p>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex flex-column gap-2">
                                <a href="<?php echo e(route('producto.detalle', $producto->id)); ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="<?php echo e(route('DeleteFav', $producto->id)); ?>" method="POST" class="m-0">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/cliente/favoritos.blade.php ENDPATH**/ ?>