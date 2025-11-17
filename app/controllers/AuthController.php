<?php

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    /**
     * Mostrar formulario de login
     */
    public function login() {
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . APP_URL);
            exit;
        }

        require_once VIEW_PATH . '/auth/login.php';
    }

    /**
     * Procesar login
     */
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Usuario y contraseña son requeridos';
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }

     
        $usuario = $this->usuarioModel->validarCredenciales($username, $password);

        if ($usuario) {
            if (!$usuario['activo']) {
                $_SESSION['error'] = 'Usuario inactivo. Contacte al administrador.';
                header('Location: ' . APP_URL . '/auth/login');
                exit;
            }

            // Crear sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_username'] = $usuario['username'];
            $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            $_SESSION['usuario_email'] = $usuario['email'];

            // Actualizar último acceso
            $this->usuarioModel->actualizarUltimoAcceso($usuario['id']);

            // Redirigir al dashboard
            $_SESSION['success'] = '¡Bienvenido ' . $usuario['nombre_completo'] . '!';
            header('Location: ' . APP_URL);
        } else {
            $_SESSION['error'] = 'Usuario o contraseña incorrectos';
            header('Location: ' . APP_URL . '/auth/login');
        }
        exit;
    }

    /**
     * Cerrar sesión
     */
    public function logout() {
        session_destroy();
        session_start();
        $_SESSION['success'] = 'Sesión cerrada exitosamente';

        header('Location: ' . APP_URL . '/auth/login');
        exit;
    }

    /**
     * Verificar si el usuario está autenticado
     */
    public static function verificarSesion() {
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['error'] = 'Debe iniciar sesión para acceder';
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public static function verificarRol($roles) {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        if (!in_array($_SESSION['usuario_rol'], $roles)) {
            $_SESSION['error'] = 'No tiene permisos para acceder a esta sección';
            header('Location: ' . APP_URL);
            exit;
        }
    }
}
