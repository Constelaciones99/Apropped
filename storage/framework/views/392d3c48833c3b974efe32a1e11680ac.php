<?php $__env->startSection('content'); ?>
    <div class="row">
    <!-- Producto más comprado -->
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
        <div class="card-header">Producto Más Comprado</div>
        <div class="card-body">
            <h5 class="card-title">Nombre del Producto</h5>
            <p class="card-text">Cantidad vendida: 150</p>
        </div>
        </div>
    </div>

    <!-- Producto más buscado -->
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
        <div class="card-header">Producto Más Buscado</div>
        <div class="card-body">
            <h5 class="card-title">Nombre del Producto</h5>
            <p class="card-text">Número de búsquedas: 200</p>
        </div>
        </div>
    </div>

    <!-- Ingresos de hoy -->
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
        <div class="card-header">Ingresos de Hoy</div>
        <div class="card-body">
            <h5 class="card-title">S/ 1,500.00</h5>
            <p class="card-text">Comparado con ayer: +10%</p>
        </div>
        </div>
    </div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/products/dashboard.blade.php ENDPATH**/ ?>