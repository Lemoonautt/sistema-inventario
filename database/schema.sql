-- =====================================================
-- SISTEMA DE INVENTARIO - BASE DE DATOS
-- Aplicando conceptos sistémicos: entradas, procesos,
-- salidas, retroalimentación y equilibrio dinámico
-- =====================================================

CREATE DATABASE IF NOT EXISTS inventario_sistema
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE inventario_sistema;

-- Tabla: Usuarios (Control de acceso al sistema)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    rol ENUM('administrador', 'vendedor', 'almacenero') DEFAULT 'vendedor',
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- Tabla: Proveedores (Entradas del sistema)
CREATE TABLE proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    contacto VARCHAR(100),
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE,
    INDEX idx_nombre (nombre)
) ENGINE=InnoDB;

-- Tabla: Clientes (Salidas del sistema)
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    nit VARCHAR(20),
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE,
    INDEX idx_nombre (nombre),
    INDEX idx_nit (nit)
) ENGINE=InnoDB;

-- Tabla: Categorías de productos
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT
) ENGINE=InnoDB;

-- Tabla: Productos (Núcleo del sistema - inventario)
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    categoria_id INT,
    precio_compra DECIMAL(10,2) NOT NULL,
    precio_venta DECIMAL(10,2) NOT NULL,
    stock_actual INT NOT NULL DEFAULT 0,
    stock_minimo INT NOT NULL DEFAULT 5,
    stock_maximo INT NOT NULL DEFAULT 100,
    unidad_medida VARCHAR(20) DEFAULT 'unidad',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    INDEX idx_codigo (codigo),
    INDEX idx_nombre (nombre),
    INDEX idx_stock (stock_actual),
    INDEX idx_categoria (categoria_id)
) ENGINE=InnoDB;

-- Tabla: Compras (Entradas - flujo de entrada al sistema)
CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_compra VARCHAR(50) NOT NULL UNIQUE,
    proveedor_id INT NOT NULL,
    fecha_compra DATETIME NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    observaciones TEXT,
    usuario VARCHAR(50),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id),
    INDEX idx_fecha (fecha_compra),
    INDEX idx_proveedor (proveedor_id)
) ENGINE=InnoDB;

-- Tabla: Detalle de compras
CREATE TABLE detalle_compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    compra_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (compra_id) REFERENCES compras(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    INDEX idx_compra (compra_id),
    INDEX idx_producto (producto_id)
) ENGINE=InnoDB;

-- Tabla: Ventas (Salidas - flujo de salida del sistema)
CREATE TABLE ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_venta VARCHAR(50) NOT NULL UNIQUE,
    cliente_id INT,
    fecha_venta DATETIME NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    descuento DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    tipo_pago ENUM('efectivo', 'tarjeta', 'transferencia', 'credito') DEFAULT 'efectivo',
    estado ENUM('completada', 'pendiente', 'cancelada') DEFAULT 'completada',
    observaciones TEXT,
    usuario VARCHAR(50),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    INDEX idx_fecha (fecha_venta),
    INDEX idx_cliente (cliente_id),
    INDEX idx_estado (estado)
) ENGINE=InnoDB;

-- Tabla: Detalle de ventas
CREATE TABLE detalle_ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    INDEX idx_venta (venta_id),
    INDEX idx_producto (producto_id)
) ENGINE=InnoDB;

-- Tabla: Alertas (Sistema de retroalimentación - homeostasis)
-- Mantiene el equilibrio dinámico del inventario
CREATE TABLE alertas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    tipo_alerta ENUM('stock_bajo', 'stock_critico', 'producto_agotado', 'sobre_stock') NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_alerta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activa', 'resuelta', 'ignorada') DEFAULT 'activa',
    fecha_resolucion TIMESTAMP NULL,
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    INDEX idx_producto (producto_id),
    INDEX idx_estado (estado),
    INDEX idx_tipo (tipo_alerta)
) ENGINE=InnoDB;

-- Tabla: Movimientos de inventario (Registro de causalidad circular)
CREATE TABLE movimientos_inventario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    tipo_movimiento ENUM('entrada', 'salida', 'ajuste') NOT NULL,
    cantidad INT NOT NULL,
    stock_anterior INT NOT NULL,
    stock_nuevo INT NOT NULL,
    referencia_tipo ENUM('compra', 'venta', 'ajuste_manual') NOT NULL,
    referencia_id INT,
    descripcion TEXT,
    fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    usuario VARCHAR(50),
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    INDEX idx_producto (producto_id),
    INDEX idx_fecha (fecha_movimiento),
    INDEX idx_tipo (tipo_movimiento)
) ENGINE=InnoDB;

-- =====================================================
-- TRIGGERS - AUTOMATIZACIÓN DE PROCESOS SISTÉMICOS
-- =====================================================

-- Trigger: Actualizar stock después de una compra (Entrada al sistema)
DELIMITER //
CREATE TRIGGER after_detalle_compra_insert
AFTER INSERT ON detalle_compras
FOR EACH ROW
BEGIN
    DECLARE stock_anterior INT;

    -- Obtener stock actual
    SELECT stock_actual INTO stock_anterior
    FROM productos WHERE id = NEW.producto_id;

    -- Actualizar stock del producto
    UPDATE productos
    SET stock_actual = stock_actual + NEW.cantidad,
        ultima_actualizacion = CURRENT_TIMESTAMP
    WHERE id = NEW.producto_id;

    -- Registrar movimiento en historial
    INSERT INTO movimientos_inventario
        (producto_id, tipo_movimiento, cantidad, stock_anterior, stock_nuevo,
         referencia_tipo, referencia_id, descripcion, usuario)
    VALUES
        (NEW.producto_id, 'entrada', NEW.cantidad, stock_anterior,
         stock_anterior + NEW.cantidad, 'compra', NEW.compra_id,
         'Compra de mercadería', 'sistema');

    -- Resolver alerta si el stock volvió a niveles normales
    UPDATE alertas
    SET estado = 'resuelta', fecha_resolucion = CURRENT_TIMESTAMP
    WHERE producto_id = NEW.producto_id
      AND estado = 'activa'
      AND (stock_anterior + NEW.cantidad) > (SELECT stock_minimo FROM productos WHERE id = NEW.producto_id);
END//

-- Trigger: Actualizar stock después de una venta (Salida del sistema)
CREATE TRIGGER after_detalle_venta_insert
AFTER INSERT ON detalle_ventas
FOR EACH ROW
BEGIN
    DECLARE stock_anterior INT;
    DECLARE stock_nuevo INT;
    DECLARE stock_min INT;

    -- Obtener datos del producto
    SELECT stock_actual, stock_minimo INTO stock_anterior, stock_min
    FROM productos WHERE id = NEW.producto_id;

    SET stock_nuevo = stock_anterior - NEW.cantidad;

    -- Actualizar stock del producto
    UPDATE productos
    SET stock_actual = stock_nuevo,
        ultima_actualizacion = CURRENT_TIMESTAMP
    WHERE id = NEW.producto_id;

    -- Registrar movimiento
    INSERT INTO movimientos_inventario
        (producto_id, tipo_movimiento, cantidad, stock_anterior, stock_nuevo,
         referencia_tipo, referencia_id, descripcion, usuario)
    VALUES
        (NEW.producto_id, 'salida', NEW.cantidad, stock_anterior,
         stock_nuevo, 'venta', NEW.venta_id, 'Venta de producto', 'sistema');

    -- SISTEMA DE RETROALIMENTACIÓN: Generar alertas según nivel de stock
    -- (Mecanismo de homeostasis)
    IF stock_nuevo = 0 THEN
        INSERT INTO alertas (producto_id, tipo_alerta, mensaje)
        VALUES (NEW.producto_id, 'producto_agotado',
                CONCAT('CRÍTICO: Producto agotado. Stock: 0'));
    ELSEIF stock_nuevo <= (stock_min * 0.5) THEN
        INSERT INTO alertas (producto_id, tipo_alerta, mensaje)
        VALUES (NEW.producto_id, 'stock_critico',
                CONCAT('URGENTE: Stock crítico. Stock actual: ', stock_nuevo,
                       ', Stock mínimo: ', stock_min));
    ELSEIF stock_nuevo <= stock_min THEN
        INSERT INTO alertas (producto_id, tipo_alerta, mensaje)
        VALUES (NEW.producto_id, 'stock_bajo',
                CONCAT('ATENCIÓN: Stock bajo. Stock actual: ', stock_nuevo,
                       ', Stock mínimo: ', stock_min));
    END IF;
END//

DELIMITER ;

-- =====================================================
-- DATOS INICIALES
-- =====================================================

-- Categorías predefinidas
INSERT INTO categorias (nombre, descripcion) VALUES
('Herramientas', 'Herramientas de ferretería y construcción'),
('Materiales de Construcción', 'Cemento, cal, arena, etc.'),
('Electricidad', 'Cables, enchufes, interruptores'),
('Plomería', 'Tuberías, llaves, accesorios'),
('Pinturas', 'Pinturas, brochas, rodillos'),
('Tornillería', 'Tornillos, clavos, pernos'),
('Varios', 'Productos diversos');

-- Cliente genérico para ventas sin cliente específico
INSERT INTO clientes (nombre, nit, telefono, direccion) VALUES
('Cliente General', '0', 'N/A', 'N/A');

-- =====================================================
-- VISTAS PARA REPORTES (Salidas del sistema)
-- =====================================================

-- Vista: Productos con stock bajo (Alertas del sistema)
CREATE VIEW vista_productos_stock_bajo AS
SELECT
    p.id,
    p.codigo,
    p.nombre,
    p.stock_actual,
    p.stock_minimo,
    c.nombre AS categoria,
    CASE
        WHEN p.stock_actual = 0 THEN 'AGOTADO'
        WHEN p.stock_actual <= (p.stock_minimo * 0.5) THEN 'CRÍTICO'
        ELSE 'BAJO'
    END AS nivel_alerta
FROM productos p
LEFT JOIN categorias c ON p.categoria_id = c.id
WHERE p.stock_actual <= p.stock_minimo
ORDER BY p.stock_actual ASC;

-- Vista: Resumen de ventas diarias
CREATE VIEW vista_ventas_diarias AS
SELECT
    DATE(fecha_venta) AS fecha,
    COUNT(*) AS total_ventas,
    SUM(total) AS monto_total,
    AVG(total) AS promedio_venta
FROM ventas
WHERE estado = 'completada'
GROUP BY DATE(fecha_venta)
ORDER BY fecha DESC;

-- Vista: Productos más vendidos
CREATE VIEW vista_productos_mas_vendidos AS
SELECT
    p.id,
    p.codigo,
    p.nombre,
    p.precio_venta,
    SUM(dv.cantidad) AS total_vendido,
    SUM(dv.subtotal) AS ingresos_totales
FROM productos p
INNER JOIN detalle_ventas dv ON p.id = dv.producto_id
INNER JOIN ventas v ON dv.venta_id = v.id
WHERE v.estado = 'completada'
GROUP BY p.id, p.codigo, p.nombre, p.precio_venta
ORDER BY total_vendido DESC;

-- Vista: Análisis de rentabilidad por producto
CREATE VIEW vista_rentabilidad_productos AS
SELECT
    p.id,
    p.codigo,
    p.nombre,
    p.precio_compra,
    p.precio_venta,
    (p.precio_venta - p.precio_compra) AS utilidad_unitaria,
    ROUND(((p.precio_venta - p.precio_compra) / p.precio_compra * 100), 2) AS margen_porcentual,
    p.stock_actual,
    COALESCE(SUM(dv.cantidad), 0) AS unidades_vendidas,
    COALESCE(SUM(dv.subtotal), 0) AS ingresos_totales,
    COALESCE(SUM(dv.cantidad * p.precio_compra), 0) AS costo_total,
    COALESCE(SUM(dv.subtotal) - SUM(dv.cantidad * p.precio_compra), 0) AS utilidad_total
FROM productos p
LEFT JOIN detalle_ventas dv ON p.id = dv.producto_id
LEFT JOIN ventas v ON dv.venta_id = v.id AND v.estado = 'completada'
GROUP BY p.id, p.codigo, p.nombre, p.precio_compra, p.precio_venta, p.stock_actual
ORDER BY utilidad_total DESC;
