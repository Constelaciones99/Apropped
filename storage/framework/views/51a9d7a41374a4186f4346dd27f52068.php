<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boleta</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 40px;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .logo {
            width: 80px;
            height: auto;
        }
        .title {
            flex-grow: 1;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .info-left {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .info-right {
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        .total-row td {
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-style: italic;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="<?php echo e(public_path('images/loguino.jpg')); ?>" alt="Logo" class="logo">
        <div class="title">APROPPED</div>
    </div>

    <div class="info-row">
        <div class="info-left">
            <p><strong>Boleta Nº:</strong> <?php echo e($boleta->id); ?></p>
            <p><strong>Cliente:</strong> <?php echo e($cliente); ?></p>
            <p><strong>Vendedor:</strong> <?php echo e($vendedor->nombre ?? 'N/A'); ?></p>
        </div>
        <div class="info-right">
            <p><strong>Fecha:</strong> <?php echo e($boleta->fecha); ?></p>
        </div>
    </div>

    <hr>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $subtotal = $producto['precio'] * $producto['cantidad'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?php echo e($producto['nombre']); ?></td>
                    <td>S/. <?php echo e(number_format($producto['precio'], 2)); ?></td>
                    <td><?php echo e($producto['cantidad']); ?></td>
                    <td>S/. <?php echo e(number_format($subtotal, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td>S/. <?php echo e(number_format($total, 2)); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        ___________________________<br>
        Gracias por su compra :)
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\apropped\resources\views/boletas/pdf.blade.php ENDPATH**/ ?>