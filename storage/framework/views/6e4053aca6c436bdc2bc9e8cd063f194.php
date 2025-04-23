<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-6 border">
            <?php if($producto->imagenes->count()): ?>
                <div id="carouselProducto" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" style="overflow: hidden;">
                        <?php $__currentLoopData = $producto->imagenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $imagen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                                <img src="<?php echo e(asset('storage/' . $imagen->ruta)); ?>" class="d-block w-100 h-100 object-fit-cover" alt="Imagen del producto">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <?php if($producto->imagenes->count() > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselProducto" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselProducto" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <img src="<?php echo e(asset('images/default.jpg')); ?>" class="img-fluid" alt="Imagen por defecto">
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <h1 class="my-2"><?php echo e($producto->nombre); ?></h1>
            <hr>
            <h4>Descripcion:</h4>
            <p><i class="fa-solid fa-hand-point-right"></i> <?php echo e($producto->descripcion); ?></p>
            <hr>
            <h4>Precio Unitario:</h4>
            <h5><i class="fa-solid fa-hand-point-right"></i> S/. <?php echo e(number_format($producto->precio, 2)); ?></h5>

            <form action="<?php echo e(route('carrito.agregar', $producto->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-cart-plus"></i> Agregar al Carrito </button>
                <a class="btn btn-dark" href="<?php echo e(route('home')); ?>">Seguir buscando</a>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const precioUnitario = <?php echo e($producto->precio); ?>;
        const cantidadInput = document.getElementById("cantidad");
        const totalPrecio = document.getElementById("totalPrecio");

        cantidadInput.addEventListener("input", function () {
            let cantidad = parseInt(cantidadInput.value);
            if (isNaN(cantidad) || cantidad < 1) cantidad = 1;
            totalPrecio.textContent = "S/" + (precioUnitario * cantidad).toFixed(2);
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/cliente/show.blade.php ENDPATH**/ ?>