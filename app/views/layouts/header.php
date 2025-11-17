<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Sistema de Inventario' ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <i class="fas fa-boxes"></i>
                <span><?= APP_NAME ?></span>
            </div>
            <ul class="navbar-menu">
                <li><a href="<?= APP_URL ?>"><i class="fas fa-home"></i> Inicio</a></li>
                <li><a href="<?= APP_URL ?>/producto"><i class="fas fa-box"></i> Productos</a></li>
                <li><a href="<?= APP_URL ?>/categoria"><i class="fas fa-tags"></i> Categorías</a></li>
                <li><a href="<?= APP_URL ?>/venta"><i class="fas fa-shopping-cart"></i> Ventas</a></li>
                <li><a href="<?= APP_URL ?>/compra"><i class="fas fa-truck"></i> Compras</a></li>
                <li class="dropdown">
                    <a href="#"><i class="fas fa-users"></i> Personas <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= APP_URL ?>/cliente"><i class="fas fa-users-cog"></i> Clientes</a></li>
                        <li><a href="<?= APP_URL ?>/proveedor"><i class="fas fa-truck"></i> Proveedores</a></li>
                        <li><a href="<?= APP_URL ?>/usuario"><i class="fas fa-user-shield"></i> Usuarios</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#"><i class="fas fa-chart-bar"></i> Reportes <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= APP_URL ?>/reporte/stock">Stock</a></li>
                        <li><a href="<?= APP_URL ?>/reporte/ventas">Ventas</a></li>
                        <li><a href="<?= APP_URL ?>/reporte/utilidades">Utilidades</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#"><i class="fas fa-user-circle"></i> <?= $_SESSION['usuario_nombre'] ?? 'Usuario' ?> <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= APP_URL ?>/auth/logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="main-container">
        <!-- Sidebar (opcional para alertas) -->
        <?php
        // Mostrar alertas del sistema si existen
        if (isset($_SESSION['success'])):
        ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Contenido de la página -->
        <div class="content">
