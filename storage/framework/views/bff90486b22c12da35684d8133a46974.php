<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
         body {
            margin: 0;
            overflow: hidden;
            background-color: #000;
            color: white;
            font-family: 'Segoe UI', sans-serif;
        }

        .intro-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background-color: #000;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .intro-logo {
            width: 150px;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }

        .menu {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #000;
        }

        .menu button {
            margin: 10px;
            padding: 15px 30px;
            font-size: 1.2rem;
        }

        .admin-link {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #fff;
            font-size: 1.1rem;
            text-decoration: none;
        }

        .admin-link i {
            margin-right: 5px;
        }

        .admin-link:hover {
            color: #0f0;
        }

        .btn-card-container {
        display: flex;
        gap: 40px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 30px;
        }

        .btn-card {
            background: linear-gradient(135deg, #4caf50, #81c784);
            color: white;
            width: 180px;
            height: 180px;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: bold;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .btn-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 255, 100, 0.5);
        }

        .btn-card .icon {
            font-size: 70px;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>
    <!-- INTRO SPLASH -->

    
    <div id="intro" class="intro-screen">
        <img src="<?php echo e(asset('images/logo.jpg')); ?>" alt="Logo" class="intro-logo">
    </div>

    
    <div id="menu" class="menu">
        <div class="btn-card-container">
            <a href="<?php echo e(route('home')); ?>" class="btn-card">
                <i class="fas fa-shopping-cart icon"></i>
                <span>Comprar</span>
            </a>
            <a class="btn btn-card" data-bs-toggle="modal" data-bs-target="#modalVender">
                <i class="fas fa-store icon"></i>
                <span>Vender</span>
            </a>
        </div>

        
        <a class="admin-link" data-bs-toggle="modal" data-bs-target="#modalAdmin">
            <i class="fas fa-cogs"></i> Admin
        </a>
    </div>

    <!-- Modal de Vendedor -->
    <div class="modal fade" id="modalVender" tabindex="-1" aria-labelledby="modalVenderLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?php echo e(route('validar.vendedor')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="modalVenderLabel">Ingreso de Vendedor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="usernameVendedor" class="form-label text-dark">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="usernameVendedor" name="username" required>
                        </div>
                        <?php if(session('error')): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo e(session('error')); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Ingresar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <!-- Modal de Admin -->
    <div class="modal fade" id="modalAdmin" tabindex="-1" aria-labelledby="modalAdminLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formAdmin" method="POST" action="<?php echo e(route('admin.login')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title txt-dark text-dark" id="modalAdminLabel">Ingreso de Administrador</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 text-dark">
                            <label for="usernameAdmin" class="form-label text-dark">Usuario</label>
                            <input type="text" class="form-control" name="username" id="usernameAdmin" required>
                        </div>
                        <div class="mb-3 text-dark">
                            <label for="passwordAdmin" class="form-label text-dark">Contraseña</label>
                            <input type="password" class="form-control" id="passwordAdmin" name="password" required>
                        </div>
                        <div id="alertAdmin" class="alert alert-danger d-none" role="alert">
                            <!-- Mensaje de error -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Ingresar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const userIsLoggedIn = <?php echo json_encode(Auth::check(), 15, 512) ?>;
            const userIsAdmin = <?php echo json_encode(Auth::check() && Auth::user()->rol === 'admin', 15, 512) ?>;

            if (userIsLoggedIn && !userIsAdmin) {
                // Redirigir después de la intro si es un cliente
                setTimeout(() => {
                    window.location.href = "<?php echo e(route('home')); ?>";
                }, 3000);
            } else {
                // Mostrar menú después de la intro (admin o no autenticado)
                setTimeout(() => {
                    document.getElementById('intro').style.display = 'none';
                    document.getElementById('menu').style.display = 'flex';
                }, 2000);
            }
        });



    </script>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\apropped\resources\views/inicio.blade.php ENDPATH**/ ?>