<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-chart-line"></i>
        Reporte de Ventas
    </h1>
</div>

<div class="filters-card">
    <form method="GET" action="<?= APP_URL ?>/reporte/ventas" class="filters-form">
        <div class="form-row">
            <div class="form-group">
                <label>Desde:</label>
                <input type="date" name="fecha_desde" class="form-control" value="<?= $fecha_desde ?>">
            </div>
            <div class="form-group">
                <label>Hasta:</label>
                <input type="date" name="fecha_hasta" class="form-control" value="<?= $fecha_hasta ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
            </div>
        </div>
    </form>
</div>

<div class="stats-grid stats-small">
    <div class="stat-card stat-primary">
        <h3><?= $totales['total_ventas'] ?></h3>
        <p>Ventas Realizadas</p>
    </div>
    <div class="stat-card stat-success">
        <h3><?= MONEDA_SIMBOLO ?> <?= number_format($totales['monto_total'], 2) ?></h3>
        <p>Monto Total</p>
    </div>
    <div class="stat-card stat-info">
        <h3><?= MONEDA_SIMBOLO ?> <?= number_format($totales['promedio'], 2) ?></h3>
        <p>Promedio por Venta</p>
    </div>
</div>

<div class="table-card">
    <h3>Ventas por Día</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cantidad Ventas</th>
                <th>Monto Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ventas_por_dia as $vd): ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($vd['fecha'])) ?></td>
                <td><?= $vd['total_ventas'] ?></td>
                <td><?= MONEDA_SIMBOLO ?> <?= number_format($vd['monto_total'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
