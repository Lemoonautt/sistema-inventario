<?php


class Compra {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todas las compras
     */
    public function obtenerTodas($filtros = []) {
        $sql = "SELECT c.*, p.nombre AS proveedor_nombre
                FROM compras c
                INNER JOIN proveedores p ON c.proveedor_id = p.id
                WHERE 1=1";

        $params = [];

        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND DATE(c.fecha_compra) >= :fecha_desde";
            $params[':fecha_desde'] = $filtros['fecha_desde'];
        }

        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND DATE(c.fecha_compra) <= :fecha_hasta";
            $params[':fecha_hasta'] = $filtros['fecha_hasta'];
        }

        $sql .= " ORDER BY c.fecha_compra DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener compra por ID con detalles
     */
    public function obtenerPorId($id) {
        $sql = "SELECT c.*, p.nombre AS proveedor_nombre
                FROM compras c
                INNER JOIN proveedores p ON c.proveedor_id = p.id
                WHERE c.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $compra = $stmt->fetch();

        if ($compra) {
            $compra['detalles'] = $this->obtenerDetalles($id);
        }

        return $compra;
    }

    /**
     * Obtener detalles de una compra
     */
    public function obtenerDetalles($compraId) {
        $sql = "SELECT dc.*, p.nombre AS producto_nombre, p.codigo AS producto_codigo
                FROM detalle_compras dc
                INNER JOIN productos p ON dc.producto_id = p.id
                WHERE dc.compra_id = :compra_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':compra_id' => $compraId]);
        return $stmt->fetchAll();
    }

    /**
     * Crear nueva compra
     */
    public function crear($datos, $detalles) {
        try {
            $this->db->beginTransaction();

            // Validar stock máximo para cada producto
            foreach ($detalles as $detalle) {
                $sql = "SELECT nombre, stock_actual, stock_maximo
                        FROM productos
                        WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':id' => $detalle['producto_id']]);
                $producto = $stmt->fetch();

                if ($producto) {
                    $nuevoStock = $producto['stock_actual'] + $detalle['cantidad'];
                    if ($nuevoStock > $producto['stock_maximo']) {
                        throw new Exception(
                            "El producto '{$producto['nombre']}' excedería el stock máximo. " .
                            "Stock actual: {$producto['stock_actual']}, " .
                            "Cantidad a comprar: {$detalle['cantidad']}, " .
                            "Stock máximo permitido: {$producto['stock_maximo']}. " .
                            "Stock resultante: {$nuevoStock}"
                        );
                    }
                }
            }

            // Generar número de compra
            $numeroCompra = $this->generarNumeroCompra();

            // Insertar compra
            $sql = "INSERT INTO compras
                    (numero_compra, proveedor_id, fecha_compra, total, observaciones, usuario)
                    VALUES
                    (:numero_compra, :proveedor_id, :fecha_compra, :total, :observaciones, :usuario)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':numero_compra' => $numeroCompra,
                ':proveedor_id' => $datos['proveedor_id'],
                ':fecha_compra' => $datos['fecha_compra'] ?? date('Y-m-d H:i:s'),
                ':total' => $datos['total'],
                ':observaciones' => $datos['observaciones'] ?? null,
                ':usuario' => 'admin'
            ]);

            $compraId = $this->db->lastInsertId();

            // Insertar detalles (los triggers actualizarán el stock automáticamente)
            foreach ($detalles as $detalle) {
                $this->insertarDetalle($compraId, $detalle);
            }

            $this->db->commit();
            return $compraId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Insertar detalle de compra
     */
    private function insertarDetalle($compraId, $detalle) {
        $sql = "INSERT INTO detalle_compras
                (compra_id, producto_id, cantidad, precio_unitario, subtotal)
                VALUES
                (:compra_id, :producto_id, :cantidad, :precio_unitario, :subtotal)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':compra_id' => $compraId,
            ':producto_id' => $detalle['producto_id'],
            ':cantidad' => $detalle['cantidad'],
            ':precio_unitario' => $detalle['precio_unitario'],
            ':subtotal' => $detalle['subtotal']
        ]);
    }

    /**
     * Generar número de compra único
     */
    private function generarNumeroCompra() {
        $prefijo = 'CMP-';
        $fecha = date('Ymd');

        $sql = "SELECT COUNT(*) FROM compras WHERE numero_compra LIKE :patron";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':patron' => $prefijo . $fecha . '%']);
        $count = $stmt->fetchColumn() + 1;

        return $prefijo . $fecha . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Obtener compras del mes
     */
    public function obtenerComprasMes() {
        $sql = "SELECT COUNT(*) as total_compras, COALESCE(SUM(total), 0) as total_monto
                FROM compras
                WHERE MONTH(fecha_compra) = MONTH(CURDATE())
                AND YEAR(fecha_compra) = YEAR(CURDATE())";

        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
}
