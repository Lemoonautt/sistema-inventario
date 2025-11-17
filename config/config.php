<?php
/**
 * CONFIGURACIÓN DEL SISTEMA DE INVENTARIO
 * Archivo central de configuración
 */

// Configuración de Base de Datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'inventario_sistema');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuración de la aplicación
define('APP_NAME', 'Sistema de Inventario');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/lab1/public');

// Rutas del sistema
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('VIEW_PATH', APP_PATH . '/views');
define('CONTROLLER_PATH', APP_PATH . '/controllers');
define('MODEL_PATH', APP_PATH . '/models');

// Configuración de sesión
define('SESSION_NAME', 'inventario_session');
define('SESSION_LIFETIME', 3600); // 1 hora

// Zona horaria
date_default_timezone_set('America/La_Paz');

// Configuración de errores (desarrollo/producción)
define('ENVIRONMENT', 'development'); // 'development' o 'production'

if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Configuración de alertas (Sistema de homeostasis)
define('STOCK_CRITICO_PORCENTAJE', 0.5); // 50% del stock mínimo
define('VERIFICAR_ALERTAS_AUTO', true);

// Formato de moneda
define('MONEDA_SIMBOLO', 'Bs');
define('MONEDA_FORMATO', 'BOB');
