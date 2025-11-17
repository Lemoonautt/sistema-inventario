<?php
/**
 * PUNTO DE ENTRADA DEL SISTEMA
 */

// Iniciar sesión
session_start();

// Cargar configuración (detecta si está en Docker)
if (file_exists(__DIR__ . '/../config/config.docker.php') && getenv('DB_HOST')) {
    require_once __DIR__ . '/../config/config.docker.php';
} else {
    require_once __DIR__ . '/../config/config.php';
}

// Autoload de modelos
spl_autoload_register(function($clase) {
    $archivo = MODEL_PATH . '/' . $clase . '.php';
    if (file_exists($archivo)) {
        require_once $archivo;
    }
});

// Obtener la URL solicitada
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Determinar controlador y método
$controladorNombre = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'DashboardController';
$metodo = $url[1] ?? 'index';
$parametros = array_slice($url, 2);

// Rutas públicas (no requieren autenticación)
$rutasPublicas = ['auth'];
$requiereAuth = !in_array($url[0] ?? '', $rutasPublicas);

// Verificar autenticación
if ($requiereAuth && !isset($_SESSION['usuario_id'])) {
    header('Location: ' . APP_URL . '/auth/login');
    exit;
}

// Verificar si el controlador existe
$archivoControlador = CONTROLLER_PATH . '/' . $controladorNombre . '.php';

if (file_exists($archivoControlador)) {
    require_once $archivoControlador;

    // Instanciar controlador
    $controlador = new $controladorNombre();

    // Verificar si el método existe
    if (method_exists($controlador, $metodo)) {
        call_user_func_array([$controlador, $metodo], $parametros);
    } else {
        // Método no encontrado
        mostrarError404("Método no encontrado: $metodo");
    }
} else {
    // Controlador no encontrado
    mostrarError404("Controlador no encontrado: $controladorNombre");
}

/**
 * Mostrar página de error 404
 */
function mostrarError404($mensaje = "Página no encontrada") {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error 404 - <?= APP_NAME ?></title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                color: #fff;
            }
            .error-container {
                text-align: center;
                background: rgba(255,255,255,0.1);
                padding: 50px;
                border-radius: 20px;
                backdrop-filter: blur(10px);
            }
            h1 { font-size: 120px; margin: 0; }
            h2 { font-size: 32px; margin: 10px 0; }
            p { font-size: 18px; margin: 20px 0; }
            a {
                display: inline-block;
                margin-top: 20px;
                padding: 12px 30px;
                background: #fff;
                color: #667eea;
                text-decoration: none;
                border-radius: 25px;
                font-weight: bold;
                transition: transform 0.3s;
            }
            a:hover { transform: scale(1.05); }
        </style>
    </head>
    <body>
        <div class="error-container">
            <h1>404</h1>
            <h2>Página no encontrada</h2>
            <p><?= htmlspecialchars($mensaje) ?></p>
            <p>El recurso solicitado no existe en el sistema.</p>
            <a href="<?= APP_URL ?>">Volver al Inicio</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}
