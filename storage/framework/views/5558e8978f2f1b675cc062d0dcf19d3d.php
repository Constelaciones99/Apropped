<?php $__env->startSection('content'); ?>


<div class="container mt-5">
    <h1 class="mb-4">Crear Vendedor</h1>
        <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <h5><strong>Ups! Hubo algunos errores:</strong></h5>
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
    <form action="<?php echo e(route('admin.usuarios.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre"  class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Usuario</label>
            <input type="text" name="username"  class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirmar Contraseña:</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email"  class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Cel:</label>
            <input type="number" minlength="9" maxlength="9"  name="celular"  class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Direccion:</label>
            <input type="text" name="direccion"  class="form-control" required>
        </div>

        <!-- Input oculto para rol -->
        <input type="hidden" name="rol" value="vendedor">

        <button class="btn btn-secondary text-white">
            <a href="<?php echo e(route('admin.usuarios.index')); ?>" class="text-white"/><i class="fa-solid fa-arrow-left"></i> Cancelar
        </button>

        <button class="btn btn-success">
            <i class="fas fa-check"></i> Guardar
        </button>

    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/products/createu.blade.php ENDPATH**/ ?>