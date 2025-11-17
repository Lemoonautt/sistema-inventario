<?php


class Cliente {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todos los clientes activos
     */
    public function obtenerTodos() {
        $sql = "SELECT * FROM clientes WHERE activo = 1 ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Obtener cliente por ID
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM clientes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crear cliente
     */
    public function crear($datos) {
        $sql = "INSERT INTO clientes (nombre, nit, telefono, email, direccion)
                VALUES (:nombre, :nit, :telefono, :email, :direccion)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':nit' => $datos['nit'] ?? null,
            ':telefono' => $datos['telefono'] ?? null,
            ':email' => $datos['email'] ?? null,
            ':direccion' => $datos['direccion'] ?? null
        ]);
    }

    /**
     * Actualizar cliente
     */
    public function actualizar($id, $datos) {
        $sql = "UPDATE clientes SET
                nombre = :nombre,
                nit = :nit,
                telefono = :telefono,
                email = :email,
                direccion = :direccion
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $datos['nombre'],
            ':nit' => $datos['nit'],
            ':telefono' => $datos['telefono'],
            ':email' => $datos['email'],
            ':direccion' => $datos['direccion']
        ]);
    }

    /**
     * Buscar cliente por nombre o NIT
     */
    public function buscar($termino) {
        $sql = "SELECT * FROM clientes
                WHERE activo = 1
                AND (nombre LIKE :termino1 OR nit LIKE :termino2)
                ORDER BY nombre ASC
                LIMIT 10";

        $stmt = $this->db->prepare($sql);
        $busqueda = '%' . $termino . '%';
        $stmt->execute([
            ':termino1' => $busqueda,
            ':termino2' => $busqueda
        ]);
        return $stmt->fetchAll();
    }
}
