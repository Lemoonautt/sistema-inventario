<?php

class DashboardController {
    private $productoModel;
    private $ventaModel;
    private $compraModel;
    private $alertaModel;

    public function __construct() {
        $this->productoModel = new Producto();
        $this->ventaModel = new Venta();
        $this->compraModel = new Compra();
        $this->alertaModel = new Alerta();
    }

    /**
     * Página principal del dashboard
     */
    public function index() {
        // Obtener estadísticas generales
        $estadisticasProductos = $this->productoModel->obtenerEstadisticas();
        $ventasHoy = $this->ventaModel->obtenerVentasHoy();
        $ventasMes = $this->ventaModel->obtenerVentasMes();
        $comprasMes = $this->compraModel->obtenerComprasMes();

        // Obtener alertas activas
        $alertas = $this->alertaModel->obtenerActivas();
        $resumenAlertas = $this->alertaModel->obtenerResumen();

        // Productos con stock bajo
        $productosStockBajo = $this->productoModel->obtenerStockBajo();

        // Ventas recientes
        $ventasRecientes = $this->ventaModel->obtenerTodas(['limit' => 5]);

        // Preparar datos para la vista
        $data = [
            'titulo' => 'Panel de Control',
            'estadisticas' => [
                'total_productos' => $estadisticasProductos['total_productos'] ?? 0,
                'total_stock' => $estadisticasProductos['total_stock'] ?? 0,
                'valor_inventario' => $estadisticasProductos['valor_inventario'] ?? 0,
                'productos_stock_bajo' => $estadisticasProductos['productos_stock_bajo'] ?? 0,
                'ventas_hoy' => $ventasHoy['total_ventas'] ?? 0,
                'monto_ventas_hoy' => $ventasHoy['total_monto'] ?? 0,
                'ventas_mes' => $ventasMes['total_ventas'] ?? 0,
                'monto_ventas_mes' => $ventasMes['total_monto'] ?? 0,
                'compras_mes' => $comprasMes['total_compras'] ?? 0,
                'monto_compras_mes' => $comprasMes['total_monto'] ?? 0
            ],
            'alertas' => $alertas,
            'resumen_alertas' => $resumenAlertas,
            'productos_stock_bajo' => $productosStockBajo,
            'ventas_recientes' => $ventasRecientes
        ];

        $this->view('dashboard/index', $data);
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
