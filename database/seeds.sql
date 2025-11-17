-- =====================================================
-- SEEDS - DATOS DE EJEMPLO PARA EL SISTEMA
-- =====================================================

USE inventario_sistema;

-- ========== USUARIOS ==========
-- Limpiar tabla de usuarios
TRUNCATE TABLE usuarios;

-- Insertar usuarios de ejemplo
-- IMPORTANTE: Todas las contraseñas son "admin123"
INSERT INTO usuarios (username, password, nombre_completo, email, rol, activo) VALUES
('admin', '$2y$10$bhRhOKhgKLRqfeLcSWzpauXqSwOgI3AQOMyuwCE0JBeo4kqRq0Xn2', 'Administrador del Sistema', 'admin@inventario.com', 'administrador', 1),
('vendedor', '$2y$10$bhRhOKhgKLRqfeLcSWzpauXqSwOgI3AQOMyuwCE0JBeo4kqRq0Xn2', 'Juan Pérez Vendedor', 'vendedor@inventario.com', 'vendedor', 1),
('almacen', '$2y$10$bhRhOKhgKLRqfeLcSWzpauXqSwOgI3AQOMyuwCE0JBeo4kqRq0Xn2', 'María González Almacén', 'almacen@inventario.com', 'almacenero', 1);

-- ========== CATEGORÍAS ==========
INSERT INTO categorias (nombre, descripcion) VALUES
('Herramientas', 'Herramientas de mano y eléctricas'),
('Electrónica', 'Productos electrónicos y componentes'),
('Oficina', 'Artículos de oficina y papelería'),
('Ferretería', 'Productos de ferretería general')
ON DUPLICATE KEY UPDATE nombre=nombre;

-- ========== PROVEEDORES ==========
INSERT INTO proveedores (nombre, contacto, telefono, email, direccion) VALUES
('Distribuidora La Paz', 'Carlos Rodríguez', '71234567', 'ventas@distribuidoralapaz.com', 'Av. 6 de Agosto #123, La Paz'),
('Importadora El Alto', 'Ana Mamani', '72345678', 'contacto@importadoraelalto.com', 'Zona 16 de Julio, El Alto'),
('Comercial Santa Cruz', 'Roberto Silva', '73456789', 'info@comercialsc.com', 'Av. Cristo Redentor #456, Santa Cruz')
ON DUPLICATE KEY UPDATE nombre=nombre;

-- ========== CLIENTES ==========
-- Cliente General ya existe en schema.sql, solo agregamos los demás
INSERT INTO clientes (nombre, nit, telefono, email, direccion) VALUES
('Empresa ABC S.R.L.', '1234567890', '71111111', 'contacto@empresaabc.com', 'Calle Comercio #100, La Paz'),
('Constructora XYZ', '9876543210', '72222222', 'ventas@constructoraxyz.com', 'Av. Camacho #200, La Paz'),
('Comercial Los Andes', '5555555555', '73333333', 'info@losandes.com', 'Calle Murillo #300, Cochabamba')
ON DUPLICATE KEY UPDATE nombre=nombre;

-- ========== PRODUCTOS ==========
INSERT INTO productos (codigo, nombre, descripcion, categoria_id, precio_compra, precio_venta, stock_actual, stock_minimo, stock_maximo, unidad_medida) VALUES
('MART-001', 'Martillo 500g', 'Martillo de acero con mango de fibra', 1, 25.00, 45.00, 50, 10, 100, 'unidad'),
('DEST-001', 'Destornillador Phillips', 'Destornillador estrella profesional', 1, 8.00, 15.00, 75, 15, 150, 'unidad'),
('CABLE-001', 'Cable USB-C 1m', 'Cable USB tipo C de 1 metro', 2, 12.00, 25.00, 100, 20, 200, 'unidad'),
('MOUSE-001', 'Mouse Inalámbrico', 'Mouse óptico inalámbrico 2.4GHz', 2, 35.00, 65.00, 40, 10, 80, 'unidad'),
('LAPIZ-001', 'Lápiz HB Caja x12', 'Caja de 12 lápices HB', 3, 15.00, 28.00, 60, 15, 120, 'caja'),
('CUAD-001', 'Cuaderno 100 hojas', 'Cuaderno universitario 100 hojas', 3, 8.00, 18.00, 80, 20, 150, 'unidad'),
('PINZA-001', 'Pinza Universal 8"', 'Pinza universal acero forjado 8 pulgadas', 4, 28.00, 52.00, 35, 10, 70, 'unidad'),
('CINTA-001', 'Cinta Métrica 5m', 'Cinta métrica retráctil 5 metros', 4, 18.00, 35.00, 45, 10, 90, 'unidad')
ON DUPLICATE KEY UPDATE codigo=codigo;

-- Mostrar resumen
SELECT 'RESUMEN DE DATOS INSERTADOS' AS '';
SELECT COUNT(*) AS 'Usuarios creados' FROM usuarios;
SELECT COUNT(*) AS 'Categorías' FROM categorias;
SELECT COUNT(*) AS 'Proveedores' FROM proveedores;
SELECT COUNT(*) AS 'Clientes' FROM clientes;
SELECT COUNT(*) AS 'Productos' FROM productos;

SELECT '' AS '';
SELECT 'CREDENCIALES DE ACCESO' AS '';
SELECT '========================' AS '';
SELECT 'Usuario: admin' AS '';
SELECT 'Contraseña: admin123' AS '';
SELECT '========================' AS '';
