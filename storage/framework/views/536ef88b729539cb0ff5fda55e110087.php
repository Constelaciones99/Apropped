<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2>Editar Producto</h2>

    <form action="<?php echo e(route('products.update', $producto->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo e($producto->nombre); ?>" required>
        </div>

        
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4" required><?php echo e($producto->descripcion); ?></textarea>
        </div>

        
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" name="precio" class="form-control" value="<?php echo e($producto->precio); ?>" step="0.01" required>
        </div>

        
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="<?php echo e($producto->stock); ?>" required>
        </div>

        
        <div class="mb-3">
            <label for="categoria_id" class="form-label">Categoría</label>
            <select name="categoria_id" class="form-select" required>
                <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($categoria->id); ?>" <?php echo e($producto->categoria_id == $categoria->id ? 'selected' : ''); ?>>
                        <?php echo e($categoria->nombre); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <div class="mb-3">
            <label class="form-label">Imagen principal actual</label><br>
            <?php
                $mainImage = $producto->imagenes->where('es_principal', true)->first();
            ?>
            <?php if($mainImage): ?>
                <img src="<?php echo e(asset('storage/' . $mainImage->ruta)); ?>" alt="Imagen principal" width="150">
            <?php else: ?>
                <p>No tiene imagen principal.</p>
            <?php endif; ?>
        </div>

        
        <div class="mb-3">
            <label for="imagen_principal" class="form-label">Cambiar imagen principal</label>
            <input type="file" name="imagen_principal" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" multiple>
        </div>

        
        <div class="mb-3">
    <label for="imagenes" class="form-label">Agregar nuevas imágenes</label>
    <input type="file" name="imagenes[]" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"  multiple>
        </div>

        <button type="submit" class="btn btn-warning mb-3">
    <i class="fas fa-sync-alt"></i> Actualizar
</button>
        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-secondary mb-3">
    <i class="fas fa-times"></i> Cancelar
</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/products/edit.blade.php ENDPATH**/ ?>