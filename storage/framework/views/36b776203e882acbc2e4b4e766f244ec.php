<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <a href="<?php echo e(route('admin.index')); ?>" class="btn btn-secondary mb-3">← Volver a la lista</a>

    <div class="card shadow">
        <div class="row g-0">
    <div class="col-md-7">
        <div class="card-body">

                <h1><?php echo e($producto->nombre); ?></h1>
                <p><?php echo e($producto->descripcion); ?></p>
                <p><strong>Precio:</strong> $<?php echo e($producto->precio); ?></p>
                <p><strong>Stock:</strong> <?php echo e($producto->stock); ?></p>
                <p><strong>Categoría:</strong> <?php echo e($producto->categoria->nombre); ?></p>
                <!-- Galería de imágenes -->
                <h4>Galería de Imágenes</h4>
                <div class="row">
    <?php $__currentLoopData = $producto->imagenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imagen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-3 mb-3">
            <div class="card">
                <img src="<?php echo e(asset('storage/' . $imagen->ruta)); ?>" class="card-img-top" alt="Imagen del producto">

                <div class="card-body text-center">
                    <?php if(!$imagen->es_principal): ?>
                        <form action="<?php echo e(route('products.setPrincipal', [$producto->id, $imagen->id])); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button class="btn btn-sm btn-outline-success mb-1">Hacer principal</button>
                        </form>

                        <form action="<?php echo e(route('products.deleteImage', [$producto->id, $imagen->id])); ?>" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta imagen?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                        </form>
                    <?php else: ?>
                        <span class="badge bg-primary">Principal</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

               <div class="d-flex align-items-center mb-3" style="gap: 10px;">
    <a href="<?php echo e(route('products.edit', $producto->id)); ?>" class="btn btn-warning">
        <i class="fas fa-edit"></i> Editar Producto
    </a>

    <form action="<?php echo e(route('products.destroy', $producto->id)); ?>" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i> Eliminar Producto
        </button>
    </form>
</div>


        </div>
    </div>
        </div>
    </div>
</div>
<hr>
<h5 class="mt-4">Agregar imagen al producto</h5>
<form action="<?php echo e(route('products.uploadImage', $producto->id)); ?>" method="POST" enctype="multipart/form-data" id="uploadForm">
    <?php echo csrf_field(); ?>
    <label class="btn btn-secondary">
        <i class="fas fa-upload"></i> Elegir archivo
        <input type="file" name="imagen" hidden id="imagenInput">
    </label>

    <button type="submit" class="btn btn-success" id="submitBtn" disabled>
        <i class="fas fa-cloud-upload-alt"></i> Subir imagen
    </button>
</form>



<script>
    // Espera a que el DOM esté listo
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('imagenInput');
        const submitBtn = document.getElementById('submitBtn');

        fileInput.addEventListener('change', function () {
            // Si se selecciona un archivo, activar el botón
            submitBtn.disabled = !fileInput.files.length;
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/products/show.blade.php ENDPATH**/ ?>