-- =====================================================
-- DATOS DE EJEMPLO - USUARIOS
-- =====================================================

USE inventario_sistema;

-- Insertar usuarios de ejemplo
-- Contraseña por defecto para todos: "admin123"
INSERT INTO usuarios (username, password, nombre_completo, email, rol, activo) VALUES
('admin', '$2y$10$bhRhOKhgKLRqfeLcSWzpauXqSwOgI3AQOMyuwCE0JBeo4kqRq0Xn2', 'Administrador del Sistema', 'admin@inventario.com', 'administrador', 1),
('vendedor1', '$2y$10$bhRhOKhgKLRqfeLcSWzpauXqSwOgI3AQOMyuwCE0JBeo4kqRq0Xn2', 'Juan Pérez', 'juan.perez@inventario.com', 'vendedor', 1),
('vendedor2', '$2y$10$bhRhOKhgKLRqfeLcSWzpauXqSwOgI3AQOMyuwCE0JBeo4kqRq0Xn2', 'María García', 'maria.garcia@inventario.com', 'vendedor', 1),
('almacen1', '$2y$10$bhRhOKhgKLRqfeLcSWzpauXqSwOgI3AQOMyuwCE0JBeo4kqRq0Xn2', 'Carlos López', 'carlos.lopez@inventario.com', 'almacenero', 1);

-- Nota: La contrasña 'admin123' está hasheada con bcrypt

