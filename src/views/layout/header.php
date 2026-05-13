<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Informática</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css">
    
    <style>
        /* Contenedor de la derecha para alinear botones */
        .nav-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Clase base para los botones de acceso */
        .btn-acceso {
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        /* Estilo específico para Iniciar Sesión */
        .btn-login {
            color: #ffffff;
            background-color: transparent;
            border: 1px solid #ffffff;
        }

        .btn-login:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Estilo específico para Registrarse */
        .btn-register {
            color: #2c3e50;
            background-color: #ffffff;
            border: 1px solid #ffffff;
        }

        .btn-register:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
        }

        /* Estilo para el nombre de usuario cuando está logueado */
        .user-name {
            color: #ffffff;
            margin-right: 10px;
            font-style: italic;
        }

        /* Botón de cerrar sesión */
        .menu-logout {
            color: #ff7675;
            text-decoration: none;
            font-weight: bold;
            border-bottom: 1px solid transparent;
        }

        .menu-logout:hover {
            border-bottom: 1px solid #ff7675;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-links">
            <a href="<?= BASE_URL ?>">Inicio</a>
            
            <?php 
                // Calculamos el total de unidades para el carrito
                $totalUnidades = 0;
                if (isset($_SESSION['carrito'])) {
                    foreach ($_SESSION['carrito'] as $cantidad) {
                        $totalUnidades += $cantidad;
                    }
                }
            ?>

            <a href="<?= BASE_URL ?>carrito" class="nav-carrito">
                🛒 Carrito (<?= $totalUnidades ?>)
            </a>
        </div>
        
        <div class="nav-user">
            <?php if (isset($_SESSION['nombre'])): ?>
                <div class="user-info">
                    <span class="user-name">Hola, <?= htmlspecialchars($_SESSION['nombre']) ?></span>
                    <a href="<?= BASE_URL ?>pedidos/mis_pedidos" style="color:white; margin-right:15px; text-decoration:none;">Mis Pedidos</a>
                    <a href="<?= BASE_URL ?>usuarios/logout" class="menu-logout">Cerrar Sesión</a>
                </div>
            <?php else: ?>
                <a href="<?= BASE_URL ?>usuarios/login" class="btn-acceso btn-login">Iniciar Sesión</a>
                <a href="<?= BASE_URL ?>usuarios/registrar" class="btn-acceso btn-register">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>
</nav>