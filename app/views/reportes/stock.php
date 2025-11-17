<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-warehouse"></i>
        Reporte de Stock e Inventario
    </h1>
</div>

<div class="stats-grid stats-small">
    <div class="stat-card stat-primary">
        <h3><?= $totales['total_productos'] ?></h3>
        <p>Productos</p>
    </div>
    <div class="stat-card stat-info">
        <h3><?= number_format($totales['total_stock']) ?></h3>
        <p>Unidades Totales</p>
    </div>
    <div class="stat-card stat-success">
        <h3><?= MONEDA_SIMBOLO ?> <?= number_format($totales['valor_total'], 2) ?></h3>
        <p>Valor Total Inventario</p>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Stock Actual</th>
                    <th>Stock Min/Max</th>
                    <th>Precio Venta</th>
                    <th>Valor Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $prod): ?>
                <tr>
                    <td><?= htmlspecialchars($prod['codigo']) ?></td>
                    <td><?= htmlspecialchars($prod['nombre']) ?></td>
                    <td><?= htmlspecialchars($prod['categoria_nombre'] ?? '-') ?></td>
                    <td>
                        <span class="stock-badge <?= $prod['stock_actual'] <= $prod['stock_minimo'] ? 'stock-bajo' : 'stock-normal' ?>">
                            <?= $prod['stock_actual'] ?>
                        </span>
                    </td>
                    <td><?= $prod['stock_minimo'] ?> / <?= $prod['stock_maximo'] ?></td>
                    <td><?= MONEDA_SIMBOLO ?> <?= number_format($prod['precio_venta'], 2) ?></td>
                    <td><?= MONEDA_SIMBOLO ?> <?= number_format($prod['stock_actual'] * $prod['precio_venta'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
