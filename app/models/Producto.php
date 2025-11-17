<?php

class Producto {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todos los productos activos
     */
    public function obtenerTodos($filtros = []) {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.activo = 1";

        $params = [];

    
        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (p.nombre LIKE :busqueda1 OR p.codigo LIKE :busqueda2)";
            $termino = '%' . $filtros['busqueda'] . '%';
            $params[':busqueda1'] = $termino;
            $params[':busqueda2'] = $termino;
        }

   
        if (!empty($filtros['categoria_id'])) {
            $sql .= " AND p.categoria_id = :categoria_id";
            $params[':categoria_id'] = $filtros['categoria_id'];
        }

        $sql .= " ORDER BY p.nombre ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener producto por ID
     */
    public function obtenerPorId($id) {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Obtener producto por código
     */
    public function obtenerPorCodigo($codigo) {
        $sql = "SELECT * FROM productos WHERE codigo = :codigo AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':codigo' => $codigo]);
        return $stmt->fetch();
    }

    /**
     * Crear nuevo producto
     */
    public function crear($datos) {
        $sql = "INSERT INTO productos
                (codigo, nombre, descripcion, categoria_id, precio_compra,
                 precio_venta, stock_actual, stock_minimo, stock_maximo, unidad_medida)
                VALUES
                (:codigo, :nombre, :descripcion, :categoria_id, :precio_compra,
                 :precio_venta, :stock_actual, :stock_minimo, :stock_maximo, :unidad_medida)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':codigo' => $datos['codigo'],
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'] ?? null,
            ':categoria_id' => $datos['categoria_id'] ?? null,
            ':precio_compra' => $datos['precio_compra'],
            ':precio_venta' => $datos['precio_venta'],
            ':stock_actual' => $datos['stock_actual'] ?? 0,
            ':stock_minimo' => $datos['stock_minimo'] ?? 5,
            ':stock_maximo' => $datos['stock_maximo'] ?? 100,
            ':unidad_medida' => $datos['unidad_medida'] ?? 'unidad'
        ]);
    }

    /**
     * Actualizar producto
     */
    public function actualizar($id, $datos) {
        $sql = "UPDATE productos SET
                codigo = :codigo,
                nombre = :nombre,
                descripcion = :descripcion,
                categoria_id = :categoria_id,
                precio_compra = :precio_compra,
                precio_venta = :precio_venta,
                stock_minimo = :stock_minimo,
                stock_maximo = :stock_maximo,
                unidad_medida = :unidad_medida
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':codigo' => $datos['codigo'],
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'] ?? null,
            ':categoria_id' => $datos['categoria_id'] ?? null,
            ':precio_compra' => $datos['precio_compra'],
            ':precio_venta' => $datos['precio_venta'],
            ':stock_minimo' => $datos['stock_minimo'],
            ':stock_maximo' => $datos['stock_maximo'],
            ':unidad_medida' => $datos['unidad_medida']
        ]);
    }

    /**
     * Actualizar stock del producto
     */
    public function actualizarStock($id, $cantidad, $tipo = 'ajuste') {
        $producto = $this->obtenerPorId($id);
        if (!$producto) return false;

        $stockAnterior = $producto['stock_actual'];
        $stockNuevo = $stockAnterior + $cantidad;

        // Actualizar stock
        $sql = "UPDATE productos SET stock_actual = :stock_nuevo WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $resultado = $stmt->execute([':stock_nuevo' => $stockNuevo, ':id' => $id]);

        // Registrar movimiento
        if ($resultado) {
            $tipoMovimiento = $cantidad > 0 ? 'entrada' : 'salida';
            $this->registrarMovimiento($id, $tipoMovimiento, abs($cantidad),
                                       $stockAnterior, $stockNuevo, 'ajuste_manual');
        }

        return $resultado;
    }

    /**
     * Registrar movimiento de inventario
     */
    private function registrarMovimiento($productoId, $tipo, $cantidad, $stockAnterior,
                                         $stockNuevo, $referenciaType, $referenciaId = null) {
        $sql = "INSERT INTO movimientos_inventario
                (producto_id, tipo_movimiento, cantidad, stock_anterior, stock_nuevo,
                 referencia_tipo, referencia_id, usuario)
                VALUES
                (:producto_id, :tipo, :cantidad, :stock_anterior, :stock_nuevo,
                 :referencia_tipo, :referencia_id, :usuario)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':producto_id' => $productoId,
            ':tipo' => $tipo,
            ':cantidad' => $cantidad,
            ':stock_anterior' => $stockAnterior,
            ':stock_nuevo' => $stockNuevo,
            ':referencia_tipo' => $referenciaType,
            ':referencia_id' => $referenciaId,
            ':usuario' => 'admin' 
        ]);
    }

    /**
     * Eliminar producto (soft delete)
     */
    public function eliminar($id) {
        $sql = "UPDATE productos SET activo = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Obtener productos con stock bajo
     */
    public function obtenerStockBajo() {
        $sql = "SELECT * FROM vista_productos_stock_bajo";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Verificar si código existe
     */
    public function codigoExiste($codigo, $excluirId = null) {
        $sql = "SELECT COUNT(*) FROM productos WHERE codigo = :codigo";
        $params = [':codigo' => $codigo];

        if ($excluirId) {
            $sql .= " AND id != :id";
            $params[':id'] = $excluirId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Obtener estadísticas de productos
     */
    public function obtenerEstadisticas() {
        $sql = "SELECT
                COUNT(*) as total_productos,
                SUM(stock_actual) as total_stock,
                SUM(stock_actual * precio_venta) as valor_inventario,
                COUNT(CASE WHEN stock_actual <= stock_minimo THEN 1 END) as productos_stock_bajo
                FROM productos
                WHERE activo = 1";

        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
}
