<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h2 class="mb-4">Crear Producto</h2>
    <!-- Tu formulario aquí-->
    <form action="<?php echo e(route('products.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e("POR FAVOR SELECCIONA OTRA IMAGEN"); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="form-group mb-3">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" maxlength="27" required>
        </div>

        <div class="form-group mb-3">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" class="form-control" id="descripcion" required></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="precio">Precio</label>
            <input type="number" name="precio" class="form-control" id="precio" required>
        </div>

        <div class="form-group mb-3">
            <label for="stock">Stock</label>
            <input type="number" name="stock" class="form-control" id="stock" required>
        </div>

        <div class="form-group mb-3">
            <label for="categoria_id">Categoría</label>
            <select name="categoria_id" class="form-control" id="categoria_id" required>
                <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($categoria->id); ?>"><?php echo e($categoria->nombre); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="imagenes" class="form-label">Imágenes del producto</label>
            <input type="file" name="imagenes[]" id="imagenes" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" multiple>
        </div>

        <div class="d-flex justify-content-between mt-4">
    <a href="<?php echo e(route('admin.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Cancelar
    </a>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Agregar Producto
    </button>
</div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/products/create.blade.php ENDPATH**/ ?>