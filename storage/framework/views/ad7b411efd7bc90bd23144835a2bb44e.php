<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h1 class="mb-4">Editar Vendedor</h1>

    <form action="<?php echo e(route('admin.usuarios.update', $user->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" value="<?php echo e($user->nombre); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Usuario</label>
            <input type="text" name="username" value="<?php echo e($user->username); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo e($user->email); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Cel:</label>
            <input type="number" minlength="9" maxlength="9"  name="celular" value="<?php echo e($user->celular); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Direccion:</label>
            <input type="text" name="direccion" value="<?php echo e($user->direccion); ?>" class="form-control" required>
        </div>

        <button class="btn btn-primary" type="submit">
            <i class="fas fa-save"></i> Actualizar
        </button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/products/editu.blade.php ENDPATH**/ ?>