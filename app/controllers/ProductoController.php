<?php


class ProductoController {
    private $productoModel;
    private $categoriaModel;

    public function __construct() {
        $this->productoModel = new Producto();
        $this->categoriaModel = new Categoria();
    }

    /**
     * Listar productos
     */
    public function index() {
        $filtros = [
            'busqueda' => $_GET['busqueda'] ?? '',
            'categoria_id' => $_GET['categoria_id'] ?? ''
        ];

        $productos = $this->productoModel->obtenerTodos($filtros);
        $categorias = $this->categoriaModel->obtenerTodas();

        $data = [
            'titulo' => 'Gestión de Productos',
            'productos' => $productos,
            'categorias' => $categorias,
            'filtros' => $filtros
        ];

        $this->view('productos/index', $data);
    }

    /**
     * Formulario crear producto
     */
    public function crear() {
        $categorias = $this->categoriaModel->obtenerTodas();

        $data = [
            'titulo' => 'Nuevo Producto',
            'categorias' => $categorias
        ];

        $this->view('productos/crear', $data);
    }

    /**
     * Guardar nuevo producto
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/producto/crear');
            exit;
        }

        $datos = [
            'codigo' => $_POST['codigo'],
            'nombre' => $_POST['nombre'],
            'descripcion' => $_POST['descripcion'] ?? '',
            'categoria_id' => $_POST['categoria_id'] ?? null,
            'precio_compra' => $_POST['precio_compra'],
            'precio_venta' => $_POST['precio_venta'],
            'stock_actual' => $_POST['stock_actual'] ?? 0,
            'stock_minimo' => $_POST['stock_minimo'] ?? 5,
            'stock_maximo' => $_POST['stock_maximo'] ?? 100,
            'unidad_medida' => $_POST['unidad_medida'] ?? 'unidad'
        ];

        // Validar código único
        if ($this->productoModel->codigoExiste($datos['codigo'])) {
            $_SESSION['error'] = 'El código ya existe';
            header('Location: ' . APP_URL . '/producto/crear');
            exit;
        }

        if ($this->productoModel->crear($datos)) {
            $_SESSION['success'] = 'Producto creado exitosamente';
            header('Location: ' . APP_URL . '/producto');
        } else {
            $_SESSION['error'] = 'Error al crear producto';
            header('Location: ' . APP_URL . '/producto/crear');
        }
        exit;
    }

    /**
     * Formulario editar producto
     */
    public function editar() {
        $id = $_GET['id'] ?? 0;
        $producto = $this->productoModel->obtenerPorId($id);

        if (!$producto) {
            $_SESSION['error'] = 'Producto no encontrado';
            header('Location: ' . APP_URL . '/producto');
            exit;
        }

        $categorias = $this->categoriaModel->obtenerTodas();

        $data = [
            'titulo' => 'Editar Producto',
            'producto' => $producto,
            'categorias' => $categorias
        ];

        $this->view('productos/editar', $data);
    }

    /**
     * Actualizar producto
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/producto');
            exit;
        }

        $id = $_POST['id'];
        $datos = [
            'codigo' => $_POST['codigo'],
            'nombre' => $_POST['nombre'],
            'descripcion' => $_POST['descripcion'] ?? '',
            'categoria_id' => $_POST['categoria_id'] ?? null,
            'precio_compra' => $_POST['precio_compra'],
            'precio_venta' => $_POST['precio_venta'],
            'stock_minimo' => $_POST['stock_minimo'],
            'stock_maximo' => $_POST['stock_maximo'],
            'unidad_medida' => $_POST['unidad_medida']
        ];

        // Validar código único
        if ($this->productoModel->codigoExiste($datos['codigo'], $id)) {
            $_SESSION['error'] = 'El código ya existe';
            header('Location: ' . APP_URL . '/producto/editar?id=' . $id);
            exit;
        }

        if ($this->productoModel->actualizar($id, $datos)) {
            $_SESSION['success'] = 'Producto actualizado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar producto';
        }

        header('Location: ' . APP_URL . '/producto');
        exit;
    }

    /**
     * Eliminar producto
     */
    public function eliminar() {
        $id = $_GET['id'] ?? 0;

        if ($this->productoModel->eliminar($id)) {
            $_SESSION['success'] = 'Producto eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar producto';
        }

        header('Location: ' . APP_URL . '/producto');
        exit;
    }

    /**
     * Buscar producto 
     */
    public function buscar() {
        header('Content-Type: application/json');

        $termino = $_GET['q'] ?? '';
        $productos = $this->productoModel->obtenerTodos(['busqueda' => $termino]);

        echo json_encode($productos);
        exit;
    }

    /**
     * Obtener producto por código 
     */
    public function obtenerPorCodigo() {
        header('Content-Type: application/json');

        $codigo = $_GET['codigo'] ?? '';
        $producto = $this->productoModel->obtenerPorCodigo($codigo);

        echo json_encode($producto);
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
