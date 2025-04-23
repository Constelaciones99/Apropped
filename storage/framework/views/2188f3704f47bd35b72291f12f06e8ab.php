<?php $__env->startSection('content'); ?>
<div class="container mt-5 text-center">
    <h2>¡Gracias por tu compra!</h2>
    <p>Estamos descargando tu boleta... Serás redirigido automáticamente.</p>

    <p class="text-muted">Si la descarga no comienza, haz clic aquí:
        <a href="<?php echo e(asset('storage/boletas/boleta_' . $ordenId . '.pdf')); ?>" download>Descargar manualmente</a>
    </p>
</div>

<script>
    // Descargar PDF automáticamente
    window.onload = function () {
        const link = document.createElement('a');
        link.href = "<?php echo e(asset('storage/boletas/boleta_' . $ordenId . '.pdf')); ?>";
        link.download = "boleta_<?php echo e($ordenId); ?>.pdf";
        link.click();

        // Redirigir a home después de unos segundos
        setTimeout(() => {
            window.location.href = "<?php echo e(route('home')); ?>";
        }, 4000); // 4 segundos
    };
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\apropped\resources\views/cliente/descargar.blade.php ENDPATH**/ ?>