<?php

class ProveedorController {
    private $proveedorModel;

    public function __construct() {
        $this->proveedorModel = new Proveedor();
    }

    /**
     * Listar proveedores
     */
    public function index() {
        $proveedores = $this->proveedorModel->obtenerTodos();

        $data = [
            'titulo' => 'Gestión de Proveedores',
            'proveedores' => $proveedores
        ];

        $this->view('proveedores/index', $data);
    }

    /**
     * Formulario crear proveedor
     */
    public function crear() {
        $data = [
            'titulo' => 'Nuevo Proveedor'
        ];

        $this->view('proveedores/crear', $data);
    }

    /**
     * Guardar nuevo proveedor
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/proveedor/crear');
            exit;
        }

        try {
            if (empty($_POST['nombre'])) {
                throw new Exception('El nombre del proveedor es obligatorio');
            }

            $datos = [
                'nombre' => $_POST['nombre'],
                'contacto' => $_POST['contacto'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'direccion' => $_POST['direccion'] ?? ''
            ];

            if ($this->proveedorModel->crear($datos)) {
                $_SESSION['success'] = 'Proveedor creado exitosamente';
                header('Location: ' . APP_URL . '/proveedor');
            } else {
                throw new Exception('Error al crear proveedor');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . APP_URL . '/proveedor/crear');
        }
        exit;
    }

    /**
     * Formulario editar proveedor
     */
    public function editar() {
        $id = $_GET['id'] ?? 0;
        $proveedor = $this->proveedorModel->obtenerPorId($id);

        if (!$proveedor) {
            $_SESSION['error'] = 'Proveedor no encontrado';
            header('Location: ' . APP_URL . '/proveedor');
            exit;
        }

        $data = [
            'titulo' => 'Editar Proveedor',
            'proveedor' => $proveedor
        ];

        $this->view('proveedores/editar', $data);
    }

    /**
     * Actualizar proveedor
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/proveedor');
            exit;
        }

        try {
            $id = $_POST['id'];

            if (empty($_POST['nombre'])) {
                throw new Exception('El nombre del proveedor es obligatorio');
            }

            $datos = [
                'nombre' => $_POST['nombre'],
                'contacto' => $_POST['contacto'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'direccion' => $_POST['direccion'] ?? ''
            ];

            if ($this->proveedorModel->actualizar($id, $datos)) {
                $_SESSION['success'] = 'Proveedor actualizado exitosamente';
            } else {
                throw new Exception('Error al actualizar proveedor');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . APP_URL . '/proveedor');
        exit;
    }

    /**
     * Eliminar proveedor
     */
    public function eliminar() {
        $id = $_GET['id'] ?? 0;

        // Desactivar proveedor
        $sql = "UPDATE proveedores SET activo = 0 WHERE id = :id";
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);

        if ($stmt->execute([':id' => $id])) {
            $_SESSION['success'] = 'Proveedor desactivado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al desactivar proveedor';
        }

        header('Location: ' . APP_URL . '/proveedor');
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
