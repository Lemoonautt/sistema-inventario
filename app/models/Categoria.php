<?php

class Categoria {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todas las categorías
     */
    public function obtenerTodas() {
        $sql = "SELECT * FROM categorias ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Obtener categoría por ID
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM categorias WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crear nueva categoría
     */
    public function crear($datos) {
        $sql = "INSERT INTO categorias (nombre, descripcion)
                VALUES (:nombre, :descripcion)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'] ?? null
        ]);
    }

    /**
     * Actualizar categoría
     */
    public function actualizar($id, $datos) {
        $sql = "UPDATE categorias SET
                nombre = :nombre,
                descripcion = :descripcion
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'] ?? null
        ]);
    }

    /**
     * Eliminar categoría
     */
    public function eliminar($id) {
        // Verificar si hay productos usando esta categoría
        $sql = "SELECT COUNT(*) FROM productos WHERE categoria_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            throw new Exception("No se puede eliminar la categoría porque tiene $count producto(s) asociado(s)");
        }

        $sql = "DELETE FROM categorias WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Verificar si el nombre ya existe
     */
    public function nombreExiste($nombre, $excluirId = null) {
        $sql = "SELECT COUNT(*) FROM categorias WHERE nombre = :nombre";
        $params = [':nombre' => $nombre];

        if ($excluirId) {
            $sql .= " AND id != :id";
            $params[':id'] = $excluirId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Obtener estadísticas de la categoría
     */
    public function obtenerEstadisticas($id) {
        $sql = "SELECT
                COUNT(*) as total_productos,
                COALESCE(SUM(stock_actual), 0) as total_stock,
                COALESCE(SUM(stock_actual * precio_venta), 0) as valor_inventario
                FROM productos
                WHERE categoria_id = :id AND activo = 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
