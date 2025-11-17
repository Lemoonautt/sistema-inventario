<?php


class ClienteController {
    private $clienteModel;

    public function __construct() {
        $this->clienteModel = new Cliente();
    }

    /**
     * Listar clientes
     */
    public function index() {
        $clientes = $this->clienteModel->obtenerTodos();

        $data = [
            'titulo' => 'Gestión de Clientes',
            'clientes' => $clientes
        ];

        $this->view('clientes/index', $data);
    }

    /**
     * Formulario crear cliente
     */
    public function crear() {
        $data = [
            'titulo' => 'Nuevo Cliente'
        ];

        $this->view('clientes/crear', $data);
    }

    /**
     * Guardar nuevo cliente
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/cliente/crear');
            exit;
        }

        try {
            if (empty($_POST['nombre'])) {
                throw new Exception('El nombre del cliente es obligatorio');
            }

            $datos = [
                'nombre' => $_POST['nombre'],
                'nit' => $_POST['nit'] ?? '0',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'direccion' => $_POST['direccion'] ?? ''
            ];

            if ($this->clienteModel->crear($datos)) {
                $_SESSION['success'] = 'Cliente creado exitosamente';
                header('Location: ' . APP_URL . '/cliente');
            } else {
                throw new Exception('Error al crear cliente');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . APP_URL . '/cliente/crear');
        }
        exit;
    }

    /**
     * Formulario editar cliente
     */
    public function editar() {
        $id = $_GET['id'] ?? 0;
        $cliente = $this->clienteModel->obtenerPorId($id);

        if (!$cliente) {
            $_SESSION['error'] = 'Cliente no encontrado';
            header('Location: ' . APP_URL . '/cliente');
            exit;
        }

        $data = [
            'titulo' => 'Editar Cliente',
            'cliente' => $cliente
        ];

        $this->view('clientes/editar', $data);
    }

    /**
     * Actualizar cliente
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/cliente');
            exit;
        }

        try {
            $id = $_POST['id'];

            if (empty($_POST['nombre'])) {
                throw new Exception('El nombre del cliente es obligatorio');
            }

            $datos = [
                'nombre' => $_POST['nombre'],
                'nit' => $_POST['nit'] ?? '0',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'direccion' => $_POST['direccion'] ?? ''
            ];

            if ($this->clienteModel->actualizar($id, $datos)) {
                $_SESSION['success'] = 'Cliente actualizado exitosamente';
            } else {
                throw new Exception('Error al actualizar cliente');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . APP_URL . '/cliente');
        exit;
    }

    /**
     * Eliminar cliente
     */
    public function eliminar() {
        $id = $_GET['id'] ?? 0;

        // Desactivar cliente
        $sql = "UPDATE clientes SET activo = 0 WHERE id = :id";
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);

        if ($stmt->execute([':id' => $id])) {
            $_SESSION['success'] = 'Cliente desactivado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al desactivar cliente';
        }

        header('Location: ' . APP_URL . '/cliente');
        exit;
    }

    /**
     * Buscar clientes (AJAX)
     */
    public function buscar() {
        header('Content-Type: application/json');

        $termino = $_GET['q'] ?? '';
        $clientes = $this->clienteModel->buscar($termino);

        echo json_encode($clientes);
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
