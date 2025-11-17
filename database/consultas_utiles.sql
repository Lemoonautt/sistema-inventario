-- =====================================================
-- CONSULTAS ÚTILES PARA EL SISTEMA DE INVENTARIO
-- Queries para administracion y análisis
-- =====================================================

-- ========== CONSULTAS DE PRODUCTOS ==========

-- Ver todos los productos con stock bajo
SELECT
    codigo,
    nombre,
    stock_actual,
    stock_minimo,
    (stock_minimo - stock_actual) AS faltante
FROM productos
WHERE stock_actual <= stock_minimo
ORDER BY stock_actual ASC;

-- Productos más vendidos del mes
SELECT
    p.nombre,
    SUM(dv.cantidad) AS total_vendido,
    SUM(dv.subtotal) AS ingresos
FROM productos p
INNER JOIN detalle_ventas dv ON p.id = dv.producto_id
INNER JOIN ventas v ON dv.venta_id = v.id
WHERE MONTH(v.fecha_venta) = MONTH(CURDATE())
  AND YEAR(v.fecha_venta) = YEAR(CURDATE())
  AND v.estado = 'completada'
GROUP BY p.id, p.nombre
ORDER BY total_vendido DESC
LIMIT 10;

-- Productos sin movimiento en 30 días
SELECT
    p.codigo,
    p.nombre,
    p.stock_actual,
    MAX(m.fecha_movimiento) AS ultimo_movimiento
FROM productos p
LEFT JOIN movimientos_inventario m ON p.id = m.producto_id
GROUP BY p.id
HAVING ultimo_movimiento < DATE_SUB(NOW(), INTERVAL 30 DAY)
    OR ultimo_movimiento IS NULL;

-- Valor total del inventario por categoria
SELECT
    c.nombre AS categoria,
    COUNT(p.id) AS total_productos,
    SUM(p.stock_actual) AS total_unidades,
    SUM(p.stock_actual * p.precio_venta) AS valor_total
FROM productos p
LEFT JOIN categorias c ON p.categoria_id = c.id
WHERE p.activo = 1
GROUP BY c.id, c.nombre
ORDER BY valor_total DESC;


-- ========== CONSULTAS DE VENTAS ==========

-- Ventas del día actual
SELECT
    numero_venta,
    c.nombre AS cliente,
    total,
    tipo_pago,
    DATE_FORMAT(fecha_venta, '%H:%i') AS hora
FROM ventas v
LEFT JOIN clientes c ON v.cliente_id = c.id
WHERE DATE(fecha_venta) = CURDATE()
  AND estado = 'completada'
ORDER BY fecha_venta DESC;

-- Resumen de ventas por semana
SELECT
    WEEK(fecha_venta) AS semana,
    COUNT(*) AS total_ventas,
    SUM(total) AS monto_total,
    AVG(total) AS ticket_promedio
FROM ventas
WHERE estado = 'completada'
  AND fecha_venta >= DATE_SUB(CURDATE(), INTERVAL 4 WEEK)
GROUP BY WEEK(fecha_venta)
ORDER BY semana DESC;

-- Ventas por método de pago
SELECT
    tipo_pago,
    COUNT(*) AS cantidad,
    SUM(total) AS monto_total,
    ROUND(SUM(total) / (SELECT SUM(total) FROM ventas WHERE estado = 'completada') * 100, 2) AS porcentaje
FROM ventas
WHERE estado = 'completada'
  AND MONTH(fecha_venta) = MONTH(CURDATE())
GROUP BY tipo_pago
ORDER BY monto_total DESC;

-- Top 10 clientes por compra total
SELECT
    c.nombre,
    c.nit,
    COUNT(v.id) AS total_compras,
    SUM(v.total) AS monto_total,
    AVG(v.total) AS ticket_promedio
FROM clientes c
INNER JOIN ventas v ON c.id = v.cliente_id
WHERE v.estado = 'completada'
GROUP BY c.id
ORDER BY monto_total DESC
LIMIT 10;


-- ========== CONSULTAS DE COMPRAS ==========

-- Compras del mes
SELECT
    numero_compra,
    p.nombre AS proveedor,
    fecha_compra,
    total
FROM compras c
INNER JOIN proveedores p ON c.proveedor_id = p.id
WHERE MONTH(fecha_compra) = MONTH(CURDATE())
  AND YEAR(fecha_compra) = YEAR(CURDATE())
ORDER BY fecha_compra DESC;

-- Total comprado por proveedor (año actual)
SELECT
    p.nombre AS proveedor,
    COUNT(c.id) AS total_compras,
    SUM(c.total) AS monto_total
FROM proveedores p
LEFT JOIN compras c ON p.id = c.proveedor_id
WHERE YEAR(c.fecha_compra) = YEAR(CURDATE())
GROUP BY p.id
ORDER BY monto_total DESC;


-- ========== ANÁLISIS DE RENTABILIDAD ==========

-- Margen de ganancia por producto (productos vendidos)
SELECT
    p.codigo,
    p.nombre,
    p.precio_compra,
    p.precio_venta,
    (p.precio_venta - p.precio_compra) AS utilidad_unitaria,
    ROUND((p.precio_venta - p.precio_compra) / p.precio_compra * 100, 2) AS margen_porcentual,
    COALESCE(SUM(dv.cantidad), 0) AS unidades_vendidas,
    COALESCE(SUM(dv.subtotal), 0) AS ingresos,
    COALESCE(SUM(dv.cantidad * p.precio_compra), 0) AS costos,
    COALESCE(SUM(dv.subtotal) - SUM(dv.cantidad * p.precio_compra), 0) AS utilidad_total
FROM productos p
LEFT JOIN detalle_ventas dv ON p.id = dv.producto_id
LEFT JOIN ventas v ON dv.venta_id = v.id AND v.estado = 'completada'
GROUP BY p.id
HAVING unidades_vendidas > 0
ORDER BY utilidad_total DESC;

-- Utilidad diaria (últimos 7 días)
SELECT
    DATE(v.fecha_venta) AS fecha,
    SUM(dv.subtotal) AS ingresos,
    SUM(dv.cantidad * p.precio_compra) AS costos,
    SUM(dv.subtotal - (dv.cantidad * p.precio_compra)) AS utilidad
FROM ventas v
INNER JOIN detalle_ventas dv ON v.id = dv.venta_id
INNER JOIN productos p ON dv.producto_id = p.id
WHERE v.estado = 'completada'
  AND v.fecha_venta >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY DATE(v.fecha_venta)
ORDER BY fecha DESC;

-- Comparación mensual (mes actual vs mes anterior)
SELECT
    'Mes Actual' AS periodo,
    COUNT(*) AS ventas,
    SUM(total) AS monto
FROM ventas
WHERE MONTH(fecha_venta) = MONTH(CURDATE())
  AND YEAR(fecha_venta) = YEAR(CURDATE())
  AND estado = 'completada'

UNION ALL

SELECT
    'Mes Anterior' AS periodo,
    COUNT(*) AS ventas,
    SUM(total) AS monto
FROM ventas
WHERE MONTH(fecha_venta) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
  AND YEAR(fecha_venta) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
  AND estado = 'completada';


-- ========== ALERTAS Y STOCK ==========

-- Ver todas las alertas activas con información del producto
SELECT
    a.id,
    a.tipo_alerta,
    p.codigo,
    p.nombre,
    p.stock_actual,
    p.stock_minimo,
    a.mensaje,
    a.fecha_alerta
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
    a.fecha_alerta DESC;

-- Contar alertas por tipo
SELECT
    tipo_alerta,
    COUNT(*) AS cantidad
FROM alertas
WHERE estado = 'activa'
GROUP BY tipo_alerta;

-- Productos que necesitan reposición urgente
SELECT
    codigo,
    nombre,
    stock_actual,
    stock_minimo,
    (stock_minimo * 2) AS cantidad_sugerida_compra
FROM productos
WHERE stock_actual < stock_minimo
ORDER BY stock_actual ASC;


-- ========== HISTORIAL Y AUDITORÍA ==========

-- Movimientos de un producto específico
SELECT
    m.fecha_movimiento,
    m.tipo_movimiento,
    m.cantidad,
    m.stock_anterior,
    m.stock_nuevo,
    m.referencia_tipo,
    m.descripcion
FROM movimientos_inventario m
WHERE m.producto_id = 1  -- Cambiar por ID del producto
ORDER BY m.fecha_movimiento DESC
LIMIT 50;

-- Resumen de movimientos del día
SELECT
    p.nombre,
    m.tipo_movimiento,
    SUM(m.cantidad) AS total_cantidad,
    COUNT(*) AS total_movimientos
FROM movimientos_inventario m
INNER JOIN productos p ON m.producto_id = p.id
WHERE DATE(m.fecha_movimiento) = CURDATE()
GROUP BY p.id, m.tipo_movimiento
ORDER BY p.nombre;


-- ========== MANTENIMIENTO ==========

-- Resolver alertas de productos con stock normalizado
UPDATE alertas a
INNER JOIN productos p ON a.producto_id = p.id
SET a.estado = 'resuelta',
    a.fecha_resolucion = NOW()
WHERE a.estado = 'activa'
  AND p.stock_actual > p.stock_minimo;

-- Eliminar alertas antiguas resueltas (más de 60 días)
DELETE FROM alertas
WHERE estado = 'resuelta'
  AND fecha_resolucion < DATE_SUB(NOW(), INTERVAL 60 DAY);

-- Ver tamaño de las tablas
SELECT
    table_name AS tabla,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS tamaño_mb,
    table_rows AS filas
FROM information_schema.TABLES
WHERE table_schema = 'inventario_sistema'
ORDER BY (data_length + index_length) DESC;


-- ========== ESTADÍSTICAS GENERALES ==========

-- Dashboard completo en una consulta
SELECT
    (SELECT COUNT(*) FROM productos WHERE activo = 1) AS total_productos,
    (SELECT SUM(stock_actual) FROM productos WHERE activo = 1) AS total_unidades,
    (SELECT SUM(stock_actual * precio_venta) FROM productos WHERE activo = 1) AS valor_inventario,
    (SELECT COUNT(*) FROM ventas WHERE DATE(fecha_venta) = CURDATE() AND estado = 'completada') AS ventas_hoy,
    (SELECT COALESCE(SUM(total), 0) FROM ventas WHERE DATE(fecha_venta) = CURDATE() AND estado = 'completada') AS monto_ventas_hoy,
    (SELECT COUNT(*) FROM alertas WHERE estado = 'activa') AS alertas_activas,
    (SELECT COUNT(*) FROM productos WHERE stock_actual <= stock_minimo) AS productos_stock_bajo;

-- Productos más rentables (por utilidad total)
SELECT
    p.nombre,
    (p.precio_venta - p.precio_compra) AS utilidad_unitaria,
    COALESCE(SUM(dv.cantidad), 0) AS vendidos,
    COALESCE(SUM(dv.subtotal - (dv.cantidad * p.precio_compra)), 0) AS utilidad_total
FROM productos p
LEFT JOIN detalle_ventas dv ON p.id = dv.producto_id
LEFT JOIN ventas v ON dv.venta_id = v.id AND v.estado = 'completada'
GROUP BY p.id
HAVING vendidos > 0
ORDER BY utilidad_total DESC
LIMIT 10;

-- Productos menos rentables (margen bajo pero que se venden)
SELECT
    p.nombre,
    p.precio_compra,
    p.precio_venta,
    ROUND((p.precio_venta - p.precio_compra) / p.precio_compra * 100, 2) AS margen_porcentual,
    COALESCE(SUM(dv.cantidad), 0) AS vendidos
FROM productos p
LEFT JOIN detalle_ventas dv ON p.id = dv.producto_id
LEFT JOIN ventas v ON dv.venta_id = v.id AND v.estado = 'completada'
GROUP BY p.id
HAVING vendidos > 0 AND margen_porcentual < 20
ORDER BY margen_porcentual ASC;


-- ========== EXPORTACIÓN DE DATOS ==========

-- Exportar listado completo de productos (para Excel)
SELECT
    codigo AS 'Código',
    nombre AS 'Producto',
    c.nombre AS 'Categoría',
    precio_compra AS 'Precio Compra',
    precio_venta AS 'Precio Venta',
    stock_actual AS 'Stock',
    stock_minimo AS 'Stock Mínimo',
    unidad_medida AS 'Unidad'
FROM productos p
LEFT JOIN categorias c ON p.categoria_id = c.id
WHERE p.activo = 1
ORDER BY p.nombre;

-- Exportar reporte de ventas (para análisis)
SELECT
    v.numero_venta AS 'Nº Venta',
    DATE_FORMAT(v.fecha_venta, '%d/%m/%Y %H:%i') AS 'Fecha',
    c.nombre AS 'Cliente',
    v.subtotal AS 'Subtotal',
    v.descuento AS 'Descuento',
    v.total AS 'Total',
    v.tipo_pago AS 'Tipo Pago'
FROM ventas v
LEFT JOIN clientes c ON v.cliente_id = c.id
WHERE v.estado = 'completada'
  AND MONTH(v.fecha_venta) = MONTH(CURDATE())
ORDER BY v.fecha_venta DESC;


-- ========== PROCEDIMIENTOS ÚTILES ==========

-- Verificar integridad de stock (comparar con movimientos)
SELECT
    p.codigo,
    p.nombre,
    p.stock_actual AS stock_sistema,
    (
        COALESCE((SELECT SUM(cantidad) FROM detalle_compras dc INNER JOIN compras c ON dc.compra_id = c.id WHERE dc.producto_id = p.id), 0) -
        COALESCE((SELECT SUM(cantidad) FROM detalle_ventas dv INNER JOIN ventas v ON dv.venta_id = v.id WHERE dv.producto_id = p.id AND v.estado = 'completada'), 0)
    ) AS stock_calculado,
    p.stock_actual - (
        COALESCE((SELECT SUM(cantidad) FROM detalle_compras dc INNER JOIN compras c ON dc.compra_id = c.id WHERE dc.producto_id = p.id), 0) -
        COALESCE((SELECT SUM(cantidad) FROM detalle_ventas dv INNER JOIN ventas v ON dv.venta_id = v.id WHERE dv.producto_id = p.id AND v.estado = 'completada'), 0)
    ) AS diferencia
FROM productos p
HAVING diferencia != 0;

-- =====================================================
-- FIN DE CONSULTAS ÚTILES
-- =====================================================
