<?php

class CategoriaController {
    private $categoriaModel;
    private $productoModel;

    public function __construct() {
        $this->categoriaModel = new Categoria();
        $this->productoModel = new Producto();
    }

    /**
     * Listar categorías
     */
    public function index() {
        $categorias = $this->categoriaModel->obtenerTodas();

        // Obtener estadísticas para cada categoría
        foreach ($categorias as &$categoria) {
            $stats = $this->categoriaModel->obtenerEstadisticas($categoria['id']);
            $categoria['total_productos'] = $stats['total_productos'];
            $categoria['total_stock'] = $stats['total_stock'];
            $categoria['valor_inventario'] = $stats['valor_inventario'];
        }

        $data = [
            'titulo' => 'Gestión de Categorías',
            'categorias' => $categorias
        ];

        $this->view('categorias/index', $data);
    }

    /**
     * Formulario crear categoría
     */
    public function crear() {
        $data = [
            'titulo' => 'Nueva Categoría'
        ];

        $this->view('categorias/crear', $data);
    }

    /**
     * Guardar nueva categoría
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/categoria/crear');
            exit;
        }

        $datos = [
            'nombre' => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? '')
        ];

        // Validaciones
        if (empty($datos['nombre'])) {
            $_SESSION['error'] = 'El nombre de la categoría es obligatorio';
            header('Location: ' . APP_URL . '/categoria/crear');
            exit;
        }

        // Verificar nombre único
        if ($this->categoriaModel->nombreExiste($datos['nombre'])) {
            $_SESSION['error'] = 'Ya existe una categoría con ese nombre';
            header('Location: ' . APP_URL . '/categoria/crear');
            exit;
        }

        try {
            if ($this->categoriaModel->crear($datos)) {
                $_SESSION['success'] = 'Categoría creada exitosamente';
                header('Location: ' . APP_URL . '/categoria');
            } else {
                $_SESSION['error'] = 'Error al crear la categoría';
                header('Location: ' . APP_URL . '/categoria/crear');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: ' . APP_URL . '/categoria/crear');
        }
        exit;
    }

    /**
     * Formulario editar categoría
     */
    public function editar() {
        $id = $_GET['id'] ?? 0;
        $categoria = $this->categoriaModel->obtenerPorId($id);

        if (!$categoria) {
            $_SESSION['error'] = 'Categoría no encontrada';
            header('Location: ' . APP_URL . '/categoria');
            exit;
        }

        $stats = $this->categoriaModel->obtenerEstadisticas($id);

        $data = [
            'titulo' => 'Editar Categoría',
            'categoria' => $categoria,
            'stats' => $stats
        ];

        $this->view('categorias/editar', $data);
    }

    /**
     * Actualizar categoría
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/categoria');
            exit;
        }

        $id = $_POST['id'];
        $datos = [
            'nombre' => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? '')
        ];

        // Validaciones
        if (empty($datos['nombre'])) {
            $_SESSION['error'] = 'El nombre de la categoría es obligatorio';
            header('Location: ' . APP_URL . '/categoria/editar?id=' . $id);
            exit;
        }

        // Verificar nombre único
        if ($this->categoriaModel->nombreExiste($datos['nombre'], $id)) {
            $_SESSION['error'] = 'Ya existe una categoría con ese nombre';
            header('Location: ' . APP_URL . '/categoria/editar?id=' . $id);
            exit;
        }

        try {
            if ($this->categoriaModel->actualizar($id, $datos)) {
                $_SESSION['success'] = 'Categoría actualizada exitosamente';
            } else {
                $_SESSION['error'] = 'Error al actualizar la categoría';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }

        header('Location: ' . APP_URL . '/categoria');
        exit;
    }

    /**
     * Eliminar categoría
     */
    public function eliminar() {
        $id = $_GET['id'] ?? 0;

        try {
            if ($this->categoriaModel->eliminar($id)) {
                $_SESSION['success'] = 'Categoría eliminada exitosamente';
            } else {
                $_SESSION['error'] = 'Error al eliminar la categoría';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . APP_URL . '/categoria');
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
