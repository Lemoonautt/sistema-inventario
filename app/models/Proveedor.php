<?php

class Proveedor {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todos los proveedores activos
     */
    public function obtenerTodos() {
        $sql = "SELECT * FROM proveedores WHERE activo = 1 ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Obtener proveedor por ID
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM proveedores WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crear proveedor
     */
    public function crear($datos) {
        $sql = "INSERT INTO proveedores (nombre, contacto, telefono, email, direccion)
                VALUES (:nombre, :contacto, :telefono, :email, :direccion)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':contacto' => $datos['contacto'] ?? null,
            ':telefono' => $datos['telefono'] ?? null,
            ':email' => $datos['email'] ?? null,
            ':direccion' => $datos['direccion'] ?? null
        ]);
    }

    /**
     * Actualizar proveedor
     */
    public function actualizar($id, $datos) {
        $sql = "UPDATE proveedores SET
                nombre = :nombre,
                contacto = :contacto,
                telefono = :telefono,
                email = :email,
                direccion = :direccion
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $datos['nombre'],
            ':contacto' => $datos['contacto'],
            ':telefono' => $datos['telefono'],
            ':email' => $datos['email'],
            ':direccion' => $datos['direccion']
        ]);
    }
}
