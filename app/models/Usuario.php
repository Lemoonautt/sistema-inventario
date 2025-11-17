<?php
/**
 * Modelo Usuario
 * Gestiona usuarios del sistema
 */

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todos los usuarios
     */
    public function obtenerTodos($filtros = []) {
        $sql = "SELECT id, username, nombre_completo, email, rol, activo,
                fecha_registro, ultimo_acceso FROM usuarios WHERE 1=1";

        $params = [];

        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (username LIKE :busqueda1 OR nombre_completo LIKE :busqueda2 OR email LIKE :busqueda3)";
            $termino = '%' . $filtros['busqueda'] . '%';
            $params[':busqueda1'] = $termino;
            $params[':busqueda2'] = $termino;
            $params[':busqueda3'] = $termino;
        }

        if (!empty($filtros['rol'])) {
            $sql .= " AND rol = :rol";
            $params[':rol'] = $filtros['rol'];
        }

        if (isset($filtros['activo']) && $filtros['activo'] !== '') {
            $sql .= " AND activo = :activo";
            $params[':activo'] = $filtros['activo'];
        }

        $sql .= " ORDER BY nombre_completo ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener usuario por ID
     */
    public function obtenerPorId($id) {
        $sql = "SELECT id, username, nombre_completo, email, rol, activo,
                fecha_registro, ultimo_acceso FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Obtener usuario por username
     */
    public function obtenerPorUsername($username) {
        $sql = "SELECT * FROM usuarios WHERE username = :username AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }

    /**
     * Crear usuario
     */
    public function crear($datos) {
        $sql = "INSERT INTO usuarios (username, password, nombre_completo, email, rol, activo)
                VALUES (:username, :password, :nombre_completo, :email, :rol, :activo)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':username' => $datos['username'],
            ':password' => password_hash($datos['password'], PASSWORD_DEFAULT),
            ':nombre_completo' => $datos['nombre_completo'],
            ':email' => $datos['email'],
            ':rol' => $datos['rol'],
            ':activo' => $datos['activo'] ?? 1
        ]);
    }

    /**
     * Actualizar usuario
     */
    public function actualizar($id, $datos) {
        $sql = "UPDATE usuarios SET
                username = :username,
                nombre_completo = :nombre_completo,
                email = :email,
                rol = :rol,
                activo = :activo
                WHERE id = :id";

        $params = [
            ':id' => $id,
            ':username' => $datos['username'],
            ':nombre_completo' => $datos['nombre_completo'],
            ':email' => $datos['email'],
            ':rol' => $datos['rol'],
            ':activo' => $datos['activo']
        ];

        // Si se proporciona nueva contraseña
        if (!empty($datos['password'])) {
            $sql = "UPDATE usuarios SET
                    username = :username,
                    password = :password,
                    nombre_completo = :nombre_completo,
                    email = :email,
                    rol = :rol,
                    activo = :activo
                    WHERE id = :id";
            $params[':password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Eliminar usuario (desactivar)
     */
    public function eliminar($id) {
        $sql = "UPDATE usuarios SET activo = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Verificar si username existe
     */
    public function usernameExiste($username, $idExcluir = null) {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE username = :username";
        $params = [':username' => $username];

        if ($idExcluir) {
            $sql .= " AND id != :id";
            $params[':id'] = $idExcluir;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Verificar si email existe
     */
    public function emailExiste($email, $idExcluir = null) {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $params = [':email' => $email];

        if ($idExcluir) {
            $sql .= " AND id != :id";
            $params[':id'] = $idExcluir;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Actualizar último acceso
     */
    public function actualizarUltimoAcceso($id) {
        $sql = "UPDATE usuarios SET ultimo_acceso = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Validar credenciales
     */
    public function validarCredenciales($username, $password) {
        $usuario = $this->obtenerPorUsername($username);

        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }

        return false;
    }
}
