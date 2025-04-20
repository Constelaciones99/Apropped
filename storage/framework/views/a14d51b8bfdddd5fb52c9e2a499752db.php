<?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-4 producto-item"
     data-id="<?php echo e($producto->id); ?>"
     data-precio="<?php echo e($producto->precio); ?>">
        <div class="card h-100">
            <?php
                $imagenPrincipal = $imagenes->first(function ($img) use ($producto) {
                    return $img->producto_id == $producto->id && $img->es_principal == 1;
                });
            ?>

            <?php if($imagenPrincipal): ?>
                <img src="<?php echo e(asset('storage/' . $imagenPrincipal->ruta)); ?>" class="card-img-top" alt="<?php echo e($producto->nombre); ?>">
            <?php else: ?>
                <img src="<?php echo e(asset('images/default.jpg')); ?>" class="card-img-top" alt="Sin imagen">
            <?php endif; ?>

            <div class="card-body">
                <h5 class="card-title"><?php echo e($producto->nombre); ?></h5>
                <p class="card-text">Stock: <?php echo e($producto->stock); ?></p>
                <div class="d-grid gap-2">
                    <button class="btn btn-info btn-sm" onclick="verImagenes(<?php echo e($producto->id); ?>)">
                        <i class="fas fa-images me-1"></i> Ver Imagen
                    </button>
                    <button class="btn btn-warning btn-sm" onclick="verDetalles('<?php echo e($producto->descripcion); ?>')">
                        <i class="fas fa-info-circle me-1"></i> Ver Detalles
                    </button>
                    <button class="btn btn-success btn-sm" onclick="agregarAlCarrito(<?php echo e($producto->id); ?>)">
                        <i class="fas fa-cart-plus me-1"></i> Añadir
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- Paginación -->
<div class="mt-4 mb-5 d-flex justify-content-center">
    <?php echo e($productos->links()); ?>

</div>

<script>
// Para manejar la paginación desde los enlaces generados por Laravel
    document.addEventListener('DOMContentLoaded', function() {
        // Volver a agregar los listeners a los enlaces de paginación
document.querySelectorAll('.pagination a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        let page = new URL(this.href).searchParams.get('page');
        filtrarProductos(page);
    });
});
    });


</script>
<?php /**PATH C:\xampp\htdocs\apropped\resources\views/vendedor/lista.blade.php ENDPATH**/ ?>