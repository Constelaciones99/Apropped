<?php $__env->startSection('content'); ?>
<div class="container py-5 animate__animated animate__fadeIn">
    <div class="row g-4">
        <!-- Imagenes del producto -->
        <div class="col-md-6">
            <div class="border rounded-4 overflow-hidden shadow-sm position-relative">
                <?php if($producto->imagenes->count()): ?>
                    <div id="carouselProducto" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php $__currentLoopData = $producto->imagenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $imagen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                                    <img src="<?php echo e(asset('storage/' . $imagen->ruta)); ?>" class="d-block w-100" style="object-fit: cover; height: 400px;" alt="Imagen del producto">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <?php if($producto->imagenes->count() > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselProducto" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselProducto" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <img src="<?php echo e(asset('images/no-image.png')); ?>" class="img-fluid rounded" alt="Imagen por defecto">
                <?php endif; ?>

                <!-- Botón favoritos -->
                 <form action="<?php echo e(route('toggle.show.favorito', $producto->id)); ?>" method="POST" id="favorito-form">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn p-0 border-0 bg-transparent" onclick="event.stopPropagation();">
                        <i class="fa<?php echo e(in_array($producto->id, Auth::user()->favoritos()) ? 's' : 'r'); ?> fa-heart text-danger fa-2x"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Detalles del producto -->
        <div class="col-md-6">
            <h1 class="mb-3"><?php echo e($producto->nombre); ?></h1>
            <hr>

            <h5 class="text-muted mb-2"><i class="fa-solid fa-align-left me-2"></i>Descripción:</h5>
            <p class="mb-4"><?php echo e($producto->descripcion); ?></p>

            <h5 class="text-muted mb-2"><i class="fa-solid fa-dollar-sign me-2"></i>Precio Unitario:</h5>
            <h3 class="text-success mb-4">S/ <?php echo e(number_format($producto->precio, 2)); ?></h3>

            <!-- Botones acción -->
            <form action="<?php echo e(route('carrito.agregar', $producto->id)); ?>" method="POST" class="d-flex flex-column gap-3">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-success btn-lg shadow-sm">
                    <i class="fa-solid fa-cart-plus me-2"></i> Agregar al Carrito
                </button>
                <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-dark btn-lg shadow-sm">
                    <i class="fa-solid fa-arrow-left me-2"></i> Seguir buscando
                </a>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/cliente/show.blade.php ENDPATH**/ ?>