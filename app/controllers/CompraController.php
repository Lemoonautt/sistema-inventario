<?php

class CompraController {
    private $compraModel;
    private $proveedorModel;
    private $productoModel;

    public function __construct() {
        $this->compraModel = new Compra();
        $this->proveedorModel = new Proveedor();
        $this->productoModel = new Producto();
    }

    /**
     * Listar compras
     */
    public function index() {
        $filtros = [
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];

        $compras = $this->compraModel->obtenerTodas($filtros);

        $data = [
            'titulo' => 'Gestión de Compras',
            'compras' => $compras,
            'filtros' => $filtros
        ];

        $this->view('compras/index', $data);
    }

    /**
     * Formulario nueva compra
     */
    public function nueva() {
        $proveedores = $this->proveedorModel->obtenerTodos();

        $data = [
            'titulo' => 'Nueva Compra',
            'proveedores' => $proveedores
        ];

        $this->view('compras/nueva', $data);
    }

    /**
     * Guardar nueva compra
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/compra/nueva');
            exit;
        }

        try {
            // Datos de la compra
            $datos = [
                'proveedor_id' => $_POST['proveedor_id'],
                'fecha_compra' => $_POST['fecha_compra'] ?? date('Y-m-d H:i:s'),
                'total' => $_POST['total'],
                'observaciones' => $_POST['observaciones'] ?? ''
            ];

            // Detalles de la compra
            $detalles = [];
            $productosIds = $_POST['producto_id'] ?? [];
            $cantidades = $_POST['cantidad'] ?? [];
            $preciosUnitarios = $_POST['precio_unitario'] ?? [];

            foreach ($productosIds as $index => $productoId) {
                if (!empty($productoId) && !empty($cantidades[$index])) {
                    $cantidad = $cantidades[$index];
                    $precioUnitario = $preciosUnitarios[$index];

                    $detalles[] = [
                        'producto_id' => $productoId,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $precioUnitario,
                        'subtotal' => $cantidad * $precioUnitario
                    ];
                }
            }

            if (empty($detalles)) {
                throw new Exception('Debe agregar al menos un producto');
            }

            $compraId = $this->compraModel->crear($datos, $detalles);

            $_SESSION['success'] = 'Compra registrada exitosamente. El stock se actualizó automáticamente.';
            header('Location: ' . APP_URL . '/compra/ver?id=' . $compraId);

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: ' . APP_URL . '/compra/nueva');
        }
        exit;
    }

    /**
     * Ver detalle de compra
     */
    public function ver() {
        $id = $_GET['id'] ?? 0;
        $compra = $this->compraModel->obtenerPorId($id);

        if (!$compra) {
            $_SESSION['error'] = 'Compra no encontrada';
            header('Location: ' . APP_URL . '/compra');
            exit;
        }

        $data = [
            'titulo' => 'Detalle de Compra',
            'compra' => $compra
        ];

        $this->view('compras/ver', $data);
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
