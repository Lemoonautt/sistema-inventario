<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-dollar-sign"></i>
        Reporte de Utilidades y Rentabilidad
    </h1>
</div>

<div class="stats-grid stats-small">
    <div class="stat-card stat-success">
        <h3><?= MONEDA_SIMBOLO ?> <?= number_format($totales['ingresos_total'], 2) ?></h3>
        <p>Ingresos Totales</p>
    </div>
    <div class="stat-card stat-danger">
        <h3><?= MONEDA_SIMBOLO ?> <?= number_format($totales['costos_total'], 2) ?></h3>
        <p>Costos Totales</p>
    </div>
    <div class="stat-card stat-primary">
        <h3><?= MONEDA_SIMBOLO ?> <?= number_format($totales['utilidad_total'], 2) ?></h3>
        <p>Utilidad Total</p>
    </div>
    <div class="stat-card stat-info">
        <h3><?= number_format($totales['margen_promedio'], 2) ?>%</h3>
        <p>Margen Promedio</p>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Unidades Vendidas</th>
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                    <th>Margen %</th>
                    <th>Ingresos</th>
                    <th>Costos</th>
                    <th>Utilidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $prod): ?>
                <?php if ($prod['unidades_vendidas'] > 0): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($prod['nombre']) ?></strong></td>
                    <td><?= $prod['unidades_vendidas'] ?></td>
                    <td><?= MONEDA_SIMBOLO ?> <?= number_format($prod['precio_compra'], 2) ?></td>
                    <td><?= MONEDA_SIMBOLO ?> <?= number_format($prod['precio_venta'], 2) ?></td>
                    <td>
                        <span class="badge <?= $prod['margen_porcentual'] >= 30 ? 'badge-success' : 'badge-warning' ?>">
                            <?= number_format($prod['margen_porcentual'], 2) ?>%
                        </span>
                    </td>
                    <td><?= MONEDA_SIMBOLO ?> <?= number_format($prod['ingresos_totales'], 2) ?></td>
                    <td><?= MONEDA_SIMBOLO ?> <?= number_format($prod['costo_total'], 2) ?></td>
                    <td>
                        <strong class="text-success">
                            <?= MONEDA_SIMBOLO ?> <?= number_format($prod['utilidad_total'], 2) ?>
                        </strong>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="section systemic-info">
    <h3><i class="fas fa-info-circle"></i> Análisis de Rentabilidad (Salidas del Sistema)</h3>
    <p>Este reporte muestra la <strong>utilidad real</strong> generada por cada producto, considerando
       la diferencia entre el precio de venta (salida) y el costo de compra (entrada).
       El margen de utilidad refleja la eficiencia del sistema en convertir entradas en salidas rentables.</p>
</div>
