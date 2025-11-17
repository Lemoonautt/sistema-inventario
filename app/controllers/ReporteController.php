<?php

class ReporteController {
    private $db;
    private $productoModel;
    private $ventaModel;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->productoModel = new Producto();
        $this->ventaModel = new Venta();
    }

    /**
     * Reporte de inventario actual
     */
    public function stock() {
        $filtros = [
            'categoria_id' => $_GET['categoria_id'] ?? '',
            'busqueda' => $_GET['busqueda'] ?? ''
        ];

        $productos = $this->productoModel->obtenerTodos($filtros);

        // Calcular totales
        $totalProductos = count($productos);
        $totalStock = 0;
        $valorTotal = 0;

        foreach ($productos as $producto) {
            $totalStock += $producto['stock_actual'];
            $valorTotal += $producto['stock_actual'] * $producto['precio_venta'];
        }

        $data = [
            'titulo' => 'Reporte de Stock',
            'productos' => $productos,
            'totales' => [
                'total_productos' => $totalProductos,
                'total_stock' => $totalStock,
                'valor_total' => $valorTotal
            ]
        ];

        $this->view('reportes/stock', $data);
    }

    /**
     * Reporte de ventas
     */
    public function ventas() {
        $fechaDesde = $_GET['fecha_desde'] ?? date('Y-m-01');
        $fechaHasta = $_GET['fecha_hasta'] ?? date('Y-m-d');

        $ventas = $this->ventaModel->obtenerTodas([
            'fecha_desde' => $fechaDesde,
            'fecha_hasta' => $fechaHasta,
            'estado' => 'completada'
        ]);

        // Calcular totales
        $totalVentas = count($ventas);
        $montoTotal = 0;
        $descuentoTotal = 0;

        foreach ($ventas as $venta) {
            $montoTotal += $venta['total'];
            $descuentoTotal += $venta['descuento'];
        }

        // Ventas por día
        $ventasPorDia = $this->ventaModel->obtenerVentasPorFecha($fechaDesde, $fechaHasta);

        $data = [
            'titulo' => 'Reporte de Ventas',
            'ventas' => $ventas,
            'ventas_por_dia' => $ventasPorDia,
            'fecha_desde' => $fechaDesde,
            'fecha_hasta' => $fechaHasta,
            'totales' => [
                'total_ventas' => $totalVentas,
                'monto_total' => $montoTotal,
                'descuento_total' => $descuentoTotal,
                'promedio' => $totalVentas > 0 ? $montoTotal / $totalVentas : 0
            ]
        ];

        $this->view('reportes/ventas', $data);
    }

    /**
     * Reporte de utilidades 
     */
    public function utilidades() {
        $sql = "SELECT * FROM vista_rentabilidad_productos ORDER BY utilidad_total DESC";
        $stmt = $this->db->query($sql);
        $productos = $stmt->fetchAll();

        // Calcular totales
        $ingresosTotal = 0;
        $costosTotal = 0;
        $utilidadTotal = 0;

        foreach ($productos as $producto) {
            $ingresosTotal += $producto['ingresos_totales'];
            $costosTotal += $producto['costo_total'];
            $utilidadTotal += $producto['utilidad_total'];
        }

        $margenPromedio = $ingresosTotal > 0 ? ($utilidadTotal / $ingresosTotal * 100) : 0;

        $data = [
            'titulo' => 'Reporte de Utilidades',
            'productos' => $productos,
            'totales' => [
                'ingresos_total' => $ingresosTotal,
                'costos_total' => $costosTotal,
                'utilidad_total' => $utilidadTotal,
                'margen_promedio' => $margenPromedio
            ]
        ];

        $this->view('reportes/utilidades', $data);
    }

    /**
     * Productos más vendidos
     */
    public function masVendidos() {
        $sql = "SELECT * FROM vista_productos_mas_vendidos LIMIT 20";
        $stmt = $this->db->query($sql);
        $productos = $stmt->fetchAll();

        $data = [
            'titulo' => 'Productos Más Vendidos',
            'productos' => $productos
        ];

        $this->view('reportes/mas_vendidos', $data);
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
