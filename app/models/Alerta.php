<?php

class Alerta {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener alertas activas
     */
    public function obtenerActivas() {
        $sql = "SELECT a.*, p.codigo, p.nombre AS producto_nombre,
                p.stock_actual, p.stock_minimo
                FROM alertas a
                INNER JOIN productos p ON a.producto_id = p.id
                WHERE a.estado = 'activa'
                ORDER BY
                    CASE a.tipo_alerta
                        WHEN 'producto_agotado' THEN 1
                        WHEN 'stock_critico' THEN 2
                        WHEN 'stock_bajo' THEN 3
                        ELSE 4
                    END,
                    a.fecha_alerta DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Obtener conteo de alertas por tipo
     */
    public function obtenerResumen() {
        $sql = "SELECT
                tipo_alerta,
                COUNT(*) as cantidad
                FROM alertas
                WHERE estado = 'activa'
                GROUP BY tipo_alerta";

        $stmt = $this->db->query($sql);
        $resultado = $stmt->fetchAll();

        $resumen = [
            'producto_agotado' => 0,
            'stock_critico' => 0,
            'stock_bajo' => 0,
            'sobre_stock' => 0,
            'total' => 0
        ];

        foreach ($resultado as $row) {
            $resumen[$row['tipo_alerta']] = $row['cantidad'];
            $resumen['total'] += $row['cantidad'];
        }

        return $resumen;
    }

    /**
     * Marcar alerta como resuelto
     */
    public function resolver($id) {
        $sql = "UPDATE alertas
                SET estado = 'resuelta', fecha_resolucion = CURRENT_TIMESTAMP
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Resolver todas las alertas de un product
     */
    public function resolverPorProducto($productoId) {
        $sql = "UPDATE alertas
                SET estado = 'resuelta', fecha_resolucion = CURRENT_TIMESTAMP
                WHERE producto_id = :producto_id AND estado = 'activa'";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':producto_id' => $productoId]);
    }

    /**
     * Crear alerta manualmente
     */
    public function crear($productoId, $tipo, $mensaje) {
        $sql = "INSERT INTO alertas (producto_id, tipo_alerta, mensaje)
                VALUES (:producto_id, :tipo, :mensaje)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':producto_id' => $productoId,
            ':tipo' => $tipo,
            ':mensaje' => $mensaje
        ]);
    }

    /**
     * Verificar y generar alertas para todos los productos
     * (Mecanismo de retroalimentación proactiva)
     */
    public function verificarStockGlobal() {
        $sql = "SELECT id, codigo, nombre, stock_actual, stock_minimo, stock_maximo
                FROM productos
                WHERE activo = 1";

        $stmt = $this->db->query($sql);
        $productos = $stmt->fetchAll();

        $alertasGeneradas = 0;

        foreach ($productos as $producto) {
            $alertasGeneradas += $this->verificarProducto($producto);
        }

        return $alertasGeneradas;
    }

    /**
     * Verificar stock de un producto específico
     */
    private function verificarProducto($producto) {
        $alertasGeneradas = 0;
        $sqlCheck = "SELECT COUNT(*) FROM alertas
                     WHERE producto_id = :id AND estado = 'activa'";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute([':id' => $producto['id']]);

        if ($stmtCheck->fetchColumn() > 0) {
            return 0; 
        }

        if ($producto['stock_actual'] == 0) {
            $this->crear(
                $producto['id'],
                'producto_agotado',
                "CRÍTICO: Producto {$producto['nombre']} agotado"
            );
            $alertasGeneradas++;
        } elseif ($producto['stock_actual'] <= ($producto['stock_minimo'] * 0.5)) {
            $this->crear(
                $producto['id'],
                'stock_critico',
                "URGENTE: Stock crítico de {$producto['nombre']}. Stock: {$producto['stock_actual']}"
            );
            $alertasGeneradas++;
        } elseif ($producto['stock_actual'] <= $producto['stock_minimo']) {
            $this->crear(
                $producto['id'],
                'stock_bajo',
                "Stock bajo de {$producto['nombre']}. Stock: {$producto['stock_actual']}"
            );
            $alertasGeneradas++;
        } elseif ($producto['stock_actual'] > $producto['stock_maximo']) {
            $this->crear(
                $producto['id'],
                'sobre_stock',
                "Sobre-stock de {$producto['nombre']}. Stock: {$producto['stock_actual']}"
            );
            $alertasGeneradas++;
        }

        return $alertasGeneradas;
    }
}
