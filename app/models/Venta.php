<?php

class Venta {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todas las ventas
     */
    public function obtenerTodas($filtros = []) {
        $sql = "SELECT v.*, c.nombre AS cliente_nombre
                FROM ventas v
                LEFT JOIN clientes c ON v.cliente_id = c.id
                WHERE 1=1";

        $params = [];

        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND DATE(v.fecha_venta) >= :fecha_desde";
            $params[':fecha_desde'] = $filtros['fecha_desde'];
        }

        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND DATE(v.fecha_venta) <= :fecha_hasta";
            $params[':fecha_hasta'] = $filtros['fecha_hasta'];
        }

  
        if (!empty($filtros['estado'])) {
            $sql .= " AND v.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }

        $sql .= " ORDER BY v.fecha_venta DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener venta por ID con detalles
     */
    public function obtenerPorId($id) {
        $sql = "SELECT v.*, c.nombre AS cliente_nombre, c.nit AS cliente_nit
                FROM ventas v
                LEFT JOIN clientes c ON v.cliente_id = c.id
                WHERE v.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $venta = $stmt->fetch();

        if ($venta) {
            $venta['detalles'] = $this->obtenerDetalles($id);
        }

        return $venta;
    }

    /**
     * Obtener detalles de una venta
     */
    public function obtenerDetalles($ventaId) {
        $sql = "SELECT dv.*, p.nombre AS producto_nombre, p.codigo AS producto_codigo
                FROM detalle_ventas dv
                INNER JOIN productos p ON dv.producto_id = p.id
                WHERE dv.venta_id = :venta_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':venta_id' => $ventaId]);
        return $stmt->fetchAll();
    }

    /**
     * Crear nueva venta
     */
    public function crear($datos, $detalles) {
        try {
            $this->db->beginTransaction();

        
            $numeroVenta = $this->generarNumeroVenta();

           
            $sql = "INSERT INTO ventas
                    (numero_venta, cliente_id, fecha_venta, subtotal, descuento,
                     total, tipo_pago, estado, observaciones, usuario)
                    VALUES
                    (:numero_venta, :cliente_id, :fecha_venta, :subtotal, :descuento,
                     :total, :tipo_pago, :estado, :observaciones, :usuario)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':numero_venta' => $numeroVenta,
                ':cliente_id' => $datos['cliente_id'] ?? 1, // Cliente general
                ':fecha_venta' => $datos['fecha_venta'] ?? date('Y-m-d H:i:s'),
                ':subtotal' => $datos['subtotal'],
                ':descuento' => $datos['descuento'] ?? 0,
                ':total' => $datos['total'],
                ':tipo_pago' => $datos['tipo_pago'] ?? 'efectivo',
                ':estado' => 'completada',
                ':observaciones' => $datos['observaciones'] ?? null,
                ':usuario' => 'admin'
            ]);

            $ventaId = $this->db->lastInsertId();

        
            foreach ($detalles as $detalle) {
                $this->insertarDetalle($ventaId, $detalle);
            }

            $this->db->commit();
            return $ventaId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Insertar detalle de venta
     */
    private function insertarDetalle($ventaId, $detalle) {
        $sql = "INSERT INTO detalle_ventas
                (venta_id, producto_id, cantidad, precio_unitario, subtotal)
                VALUES
                (:venta_id, :producto_id, :cantidad, :precio_unitario, :subtotal)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':venta_id' => $ventaId,
            ':producto_id' => $detalle['producto_id'],
            ':cantidad' => $detalle['cantidad'],
            ':precio_unitario' => $detalle['precio_unitario'],
            ':subtotal' => $detalle['subtotal']
        ]);
    }

    /**
     * Generar número de venta único
     */
    private function generarNumeroVenta() {
        $prefijo = 'VTA-';
        $fecha = date('Ymd');

        $sql = "SELECT COUNT(*) FROM ventas WHERE numero_venta LIKE :patron";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':patron' => $prefijo . $fecha . '%']);
        $count = $stmt->fetchColumn() + 1;

        return $prefijo . $fecha . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Obtener ventas del día
     */
    public function obtenerVentasHoy() {
        $sql = "SELECT COUNT(*) as total_ventas, COALESCE(SUM(total), 0) as total_monto
                FROM ventas
                WHERE DATE(fecha_venta) = CURDATE()
                AND estado = 'completada'";

        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }

    /**
     * Obtener ventas del mes actual
     */
    public function obtenerVentasMes() {
        $sql = "SELECT COUNT(*) as total_ventas, COALESCE(SUM(total), 0) as total_monto
                FROM ventas
                WHERE YEAR(fecha_venta) = YEAR(CURDATE())
                AND MONTH(fecha_venta) = MONTH(CURDATE())
                AND estado = 'completada'";

        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }

    /**
     * Obtener ventas por rango de fechas
     */
    public function obtenerVentasPorFecha($fechaInicio, $fechaFin) {
        $sql = "SELECT DATE(fecha_venta) as fecha,
                COUNT(*) as total_ventas,
                SUM(total) as monto_total
                FROM ventas
                WHERE DATE(fecha_venta) BETWEEN :fecha_inicio AND :fecha_fin
                AND estado = 'completada'
                GROUP BY DATE(fecha_venta)
                ORDER BY fecha DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':fecha_inicio' => $fechaInicio,
            ':fecha_fin' => $fechaFin
        ]);
        return $stmt->fetchAll();
    }

    /**
     * Anular venta
     */
    public function anular($id) {
        $sql = "UPDATE ventas SET estado = 'cancelada' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
