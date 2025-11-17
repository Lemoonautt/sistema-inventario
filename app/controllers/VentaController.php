<?php

class VentaController {
    private $ventaModel;
    private $clienteModel;
    private $productoModel;

    public function __construct() {
        $this->ventaModel = new Venta();
        $this->clienteModel = new Cliente();
        $this->productoModel = new Producto();
    }

    /**
     * Listar ventas
     */
    public function index() {
        $filtros = [
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];

        $ventas = $this->ventaModel->obtenerTodas($filtros);

        $data = [
            'titulo' => 'Gestión de Ventas',
            'ventas' => $ventas,
            'filtros' => $filtros
        ];

        $this->view('ventas/index', $data);
    }

    /**
     * Formulario nueva venta (Punto de venta)
     */
    public function nueva() {
        $clientes = $this->clienteModel->obtenerTodos();

        $data = [
            'titulo' => 'Nueva Venta',
            'clientes' => $clientes
        ];

        $this->view('ventas/nueva', $data);
    }

    /**
     * Guardar nueva venta
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/venta/nueva');
            exit;
        }

        try {
            // Datos de la venta
            $datos = [
                'cliente_id' => $_POST['cliente_id'] ?? 1,
                'subtotal' => $_POST['subtotal'],
                'descuento' => $_POST['descuento'] ?? 0,
                'total' => $_POST['total'],
                'tipo_pago' => $_POST['tipo_pago'] ?? 'efectivo',
                'observaciones' => $_POST['observaciones'] ?? ''
            ];

            // Detalles de la venta
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

            // Crear venta
            $ventaId = $this->ventaModel->crear($datos, $detalles);

            $_SESSION['success'] = 'Venta registrada exitosamente. Nº ' . $ventaId;
            header('Location: ' . APP_URL . '/venta/ver?id=' . $ventaId);

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: ' . APP_URL . '/venta/nueva');
        }
        exit;
    }

    /**
     * Ver detalle de venta
     */
    public function ver() {
        $id = $_GET['id'] ?? 0;
        $venta = $this->ventaModel->obtenerPorId($id);

        if (!$venta) {
            $_SESSION['error'] = 'Venta no encontrada';
            header('Location: ' . APP_URL . '/venta');
            exit;
        }

        $data = [
            'titulo' => 'Detalle de Venta',
            'venta' => $venta
        ];

        $this->view('ventas/ver', $data);
    }

    /**
     * Anular venta
     */
    public function anular() {
        $id = $_GET['id'] ?? 0;

        if ($this->ventaModel->anular($id)) {
            $_SESSION['success'] = 'Venta anulada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al anular venta';
        }

        header('Location: ' . APP_URL . '/venta');
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
