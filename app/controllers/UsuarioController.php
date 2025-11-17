<?php

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    /**
     * Listar usuarios
     */
    public function index() {
        $filtros = [
            'busqueda' => $_GET['busqueda'] ?? '',
            'rol' => $_GET['rol'] ?? '',
            'activo' => $_GET['activo'] ?? ''
        ];

        $usuarios = $this->usuarioModel->obtenerTodos($filtros);

        $data = [
            'titulo' => 'Gestión de Usuarios',
            'usuarios' => $usuarios,
            'filtros' => $filtros
        ];

        $this->view('usuarios/index', $data);
    }

    /**
     * Formulario crear usuario
     */
    public function crear() {
        $data = [
            'titulo' => 'Nuevo Usuario'
        ];

        $this->view('usuarios/crear', $data);
    }

    /**
     * Guardar nuevo usuario
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/usuario/crear');
            exit;
        }

        try {
            // Validar datos
            if (empty($_POST['username']) || empty($_POST['password']) ||
                empty($_POST['nombre_completo']) || empty($_POST['email'])) {
                throw new Exception('Todos los campos obligatorios deben ser completados');
            }

            // Verificar si username ya existe
            if ($this->usuarioModel->usernameExiste($_POST['username'])) {
                throw new Exception('El nombre de usuario ya existe');
            }

            // Verificar si email ya existe
            if ($this->usuarioModel->emailExiste($_POST['email'])) {
                throw new Exception('El correo electrónico ya está registrado');
            }

            $datos = [
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'nombre_completo' => $_POST['nombre_completo'],
                'email' => $_POST['email'],
                'rol' => $_POST['rol'] ?? 'vendedor',
                'activo' => $_POST['activo'] ?? 1
            ];

            if ($this->usuarioModel->crear($datos)) {
                $_SESSION['success'] = 'Usuario creado exitosamente';
                header('Location: ' . APP_URL . '/usuario');
            } else {
                throw new Exception('Error al crear usuario');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . APP_URL . '/usuario/crear');
        }
        exit;
    }

    /**
     * Formulario editar usuario
     */
    public function editar() {
        $id = $_GET['id'] ?? 0;
        $usuario = $this->usuarioModel->obtenerPorId($id);

        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            header('Location: ' . APP_URL . '/usuario');
            exit;
        }

        $data = [
            'titulo' => 'Editar Usuario',
            'usuario' => $usuario
        ];

        $this->view('usuarios/editar', $data);
    }

    /**
     * Actualizar usuario
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/usuario');
            exit;
        }

        try {
            $id = $_POST['id'];

            // Validar datos
            if (empty($_POST['username']) || empty($_POST['nombre_completo']) ||
                empty($_POST['email'])) {
                throw new Exception('Todos los campos obligatorios deben ser completados');
            }

            // Verificar si username ya existe (excluyendo el usuario actual)
            if ($this->usuarioModel->usernameExiste($_POST['username'], $id)) {
                throw new Exception('El nombre de usuario ya existe');
            }

            // Verificar si email ya existe (excluyendo el usuario actual)
            if ($this->usuarioModel->emailExiste($_POST['email'], $id)) {
                throw new Exception('El correo electrónico ya está registrado');
            }

            $datos = [
                'username' => $_POST['username'],
                'nombre_completo' => $_POST['nombre_completo'],
                'email' => $_POST['email'],
                'rol' => $_POST['rol'],
                'activo' => $_POST['activo'] ?? 0
            ];

            // Agregar contraseña solo si se proporciona
            if (!empty($_POST['password'])) {
                $datos['password'] = $_POST['password'];
            }

            if ($this->usuarioModel->actualizar($id, $datos)) {
                $_SESSION['success'] = 'Usuario actualizado exitosamente';
            } else {
                throw new Exception('Error al actualizar usuario');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . APP_URL . '/usuario');
        exit;
    }

    /**
     * Eliminar usuario
     */
    public function eliminar() {
        $id = $_GET['id'] ?? 0;

        if ($this->usuarioModel->eliminar($id)) {
            $_SESSION['success'] = 'Usuario desactivado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al desactivar usuario';
        }

        header('Location: ' . APP_URL . '/usuario');
        exit;
    }

    /**
     * Cargar vista
     */
    private function view($vista, $data = []) {
        extract($data);
        require_once VIEW_PATH . '/layouts/header.php';
        require_once VIEW_PATH . '/' . $vista . '.php';
        require_once VIEW_PATH . '/layouts/footer.php';
    }
}
