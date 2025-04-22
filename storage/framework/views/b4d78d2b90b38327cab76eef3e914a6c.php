<?php $__env->startSection('content'); ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Lista de Productos</h1>


        <div class="d-flex flex-row">
            <!-- Botón Admin -->
            <a href="<?php echo e(route('admin.usuarios.index')); ?>" class="btn btn-warning rounded-circle">
                <i class="fas fa-users-cog"></i>
            </a>

            <!-- Botón Dashboard -->
            <a href="<?php echo e(route('dashboard')); ?>" class="mx-1 btn text-white rounded-circle" style="background: #8B3FE0">
                <i class="fa-solid fa-chart-line"></i>
            </a>

            <!-- Botón Boletas -->
            <a href="<?php echo e(route('reporte')); ?>" class="btn btn-success text-white rounded-circle">
            <i class="fa-solid fa-print"></i>
            </a>
        </div>
    </div>

        <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary fw-bold mb-3">
            <i class="fa-solid fa-plus"></i> Agregar Producto
        </a>

    <!-- Buscador -->
    <form action="<?php echo e(route('admin.index')); ?>" method="GET" class="mb-4">
        <div class="row align-items-end">

            <!-- Búsqueda por nombre -->
            <div class="col-md-4">
                <label for="busqueda">Buscar producto:</label>
                <input type="text" name="busqueda" id="busqueda" class="form-control" value="<?php echo e(request('busqueda')); ?>" placeholder="Nombre del producto">
            </div>

            <!-- Ordenar por -->
            <div class="col-md-3">
                <label for="orden">Ordenar por:</label>
                <select name="orden" id="orden" class="form-control">
                    <option value="">-- Selecciona --</option>
                    <option value="aleatorio" <?php echo e(request('orden') == 'aleatorio' ? 'selected' : ''); ?>>Aleatorio</option>
                    <option value="recientes" <?php echo e(request('orden') == 'recientes' ? 'selected' : ''); ?>>Más recientes</option>
                </select>
            </div>

            <!-- Filtrar por categoría -->
            <div class="col-md-3">
                <label for="categoria">Categoría:</label>
                <select name="categoria" id="categoria" class="form-control">
                    <option value="">-- Todas --</option>
                    <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($categoria->id); ?>" <?php echo e(request('categoria') == $categoria->id ? 'selected' : ''); ?>>
                            <?php echo e($categoria->nombre); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Botón aplicar ver-->
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Aplicar
                </button>
            </div>
        </div>
    </form>




<div class="container mt-5">


    <div class="d-flex justify-content-center mt-4">
    <?php echo e($productos->links('vendor.pagination.bootstrap-4')); ?>


    </div>
    <?php if($productos->isEmpty()): ?>
        <p>No hay productos disponibles.</p>
    <?php else: ?>
        <div class="row">
            <!-- index.blade.php -->
        <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center pt-2">
                    <?php
                    $imgPrincipal = $producto->imagenes->where('es_principal', true)->first();
                    ?>

                    <?php if($imgPrincipal): ?>
                        <img src="<?php echo e(asset('storage/' . $imgPrincipal->ruta)); ?>"
                            class="card-img-top m-auto"
                            alt="Imagen principal del producto"
                            style="cursor:pointer; max-height:20rem; width:auto"
                            data-bs-toggle="modal"
                            data-bs-target="#modalProducto<?php echo e($producto->id); ?>">
                    <?php endif; ?>

                    <div class="card-body">
                            <h5 class="card-title"><?php echo e($producto->nombre); ?></h5>
                            <p class="card-text"><?php echo e(Str::limit($producto->descripcion, 80)); ?></p>

                            <!-- Mostrar Precio products-->
                            <p><strong>Precio:</strong> $<?php echo e(number_format($producto->precio, 2)); ?></p>

                            <!-- Mostrar Categoría -->
                            <p><strong>Categoría:</strong> <?php echo e($producto->categoria->nombre ?? 'Sin categoría'); ?></p>

                            <p><strong>Stock:</strong> <?php echo e($producto->stock); ?></p>

                            <a href="<?php echo e(route('products.show', $producto->id)); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Ver más
                            </a>
                        </div>
                </div>
            </div>

            <!-- Modal con slider -->
            <div class="modal fade" id="modalProducto<?php echo e($producto->id); ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div id="carouselProducto<?php echo e($producto->id); ?>" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php $__currentLoopData = $producto->imagenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $imagen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="carousel-item <?php if($index == 0): ?> active <?php endif; ?>">
                                            <img src="<?php echo e(asset('storage/' . $imagen->ruta)); ?>" class="d-block w-100" alt="...">
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carouselProducto<?php echo e($producto->id); ?>" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselProducto<?php echo e($producto->id); ?>" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/products/index.blade.php ENDPATH**/ ?>