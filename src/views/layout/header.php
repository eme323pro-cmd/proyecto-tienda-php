<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Informática</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <!-- Parte izquierda, navegación principal -->
        <div class="nav-links">
            <a href="<?= BASE_URL ?>">Inicio</a>
            <a href="<?= BASE_URL ?>contacto">Contacto</a>
            
            <a href="<?= BASE_URL ?>carrito" class="nav-carrito">
                🛒 Carrito (<?= isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0 ?>)
            </a>
        </div>
        
        <!-- Parte derecha: Usuario / Login -->
        <div class="nav-user">
            <?php if (isset($_SESSION['nombre'])): ?>
                <div class="user-info">
    
                    <span class="user-name">Hola, <?= htmlspecialchars($_SESSION['nombre']) ?></span>
                    <a href="<?= BASE_URL ?>usuarios/logout" class="menu-logout">Cerrar Sesión</a>
                </div>
            <?php else: ?>
                <a href="<?= BASE_URL ?>usuarios/login" class="nav-login">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>