<?php $__env->startSection('content'); ?>

<div class="container mt-5 pb-2">
    <!-- Información del usuario en la parte superior derecha -->
    <div class="d-flex justify-content-between">
        <?php if(auth()->check()): ?>
            <div>
                <p><strong>Nombre:</strong> <?php echo e(auth()->user()->nombre); ?></p>
                <p><strong>Celular:</strong> <?php echo e(auth()->user()->celular); ?></p>
                <p><strong>Estado:</strong>
    <span class="align-middle">
         Sesión Activa  <i class="fas fa-circle text-success me-1" style="font-size: 0.7rem;"></i>
    </span>
</p>
            </div>
            <div>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario">
                    <i class="fas fa-user-edit me-1"></i> Editar Usuario
                </button>
                <div class="mt-3 text-end">
                    <a href="<?php echo e(route('carrito.ver')); ?>" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-1"></i> Ver Carrito
                    </a>
                </div>
            </div>
        <?php else: ?>
        <div>
            <p>Registrate para realizar compras.</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistro">Registrarse</button>
        </div>
        <?php endif; ?>

        

<!-- Modal de Registro -->
<div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('cliente.registrar')); ?>" id="formRegistro">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRegistroLabel">Registro de Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">

                    
                    <div id="alertRegistro" class="alert alert-success alert-dismissible fade show d-none" role="alert">
                        Registro exitoso. ¡Bienvenido!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>

                    <div class="mb-3">
                        <label for="nombre">Nombres Completos</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="username">Nombre de Usuario</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="celular">Celular (9 dígitos)</label>
                        <input type="text" name="celular" class="form-control" pattern="[0-9]{9}" maxlength="9" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion">Dirección</label>
                        <input type="text" name="direccion" class="form-control" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Registrarse</button>
                </div>
            </div>
        </form>
    </div>
</div>

    </div>

    <hr>

</div>

<!-- Modal de edición de usuario editar-->
<?php if(auth()->check()): ?>
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        
        <form method="POST" action="<?php echo e(route('usuario.actualizar')); ?>">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="<?php echo e(auth()->user()->nombre); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo e(auth()->user()->username); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Celular</label>
                        <input type="text" name="celular" class="form-control" value="<?php echo e(auth()->user()->celular); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="<?php echo e(auth()->user()->direccion); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo e(auth()->user()->email); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Password (si deseas cambiarlo)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Editar Usuario</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>



<div class="container py-0">
    <h2 class="mb-4"><i class="fas fa-store"></i> Productos disponibles</h2>
    <hr>
    <div class="row">
        <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="<?php echo e(asset('storage/' . $producto->imagenes->first()?->ruta ?? 'default.jpg')); ?>" class="card-img-top" alt="Producto">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($producto->nombre); ?></h5>
                    <p class="card-text text-muted"><?php echo e(Str::limit($producto->descripcion, 100)); ?></p>
                    <p class="fw-bold">$<?php echo e($producto->precio); ?></p>
                    <a href="<?php echo e(route('producto.detalle', $producto->id)); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> Ver más
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<?php $__env->stopSection(); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formRegistro');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const data = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json'
                },
                body: data
            })
            .then(res => {
                if (!res.ok) throw res;
                return res.json();
            })
            .then(response => {
                if (response.success) {
                    const alert = document.getElementById('alertRegistro');
                    alert.classList.remove('d-none');

                    setTimeout(() => {
                        alert.classList.add('d-none');
                    }, 500);

                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalRegistro'));
                    setTimeout(() => modal.hide(), 500);

                    form.reset();

                    setTimeout(() => window.location.reload(), 600);
                }
            })
            .catch(async err => {
                let errorMsg = 'Error al registrar. Revisa los campos.';
                if (err.json) {
                    const jsonErr = await err.json();
                    if (jsonErr.message) errorMsg = jsonErr.message;
                }
                alert(errorMsg);
                console.error(err);
            });
        });
    }
});
</script>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/cliente/index.blade.php ENDPATH**/ ?>