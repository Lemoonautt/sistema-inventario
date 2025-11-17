<div class="dashboard">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-tachometer-alt"></i>
            Panel de Control
        </h1>
        <div class="page-subtitle">
            Bienvenido, <?= $_SESSION['usuario_nombre'] ?? 'Usuario' ?>
        </div>
    </div>

    <!-- Estadísticas Principales -->
    <div class="stats-grid">
        <!-- Ventas del Mes -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon primary">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-trend">
                    <span class="trend-label">Este Mes</span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= MONEDA_SIMBOLO ?> <?= number_format($estadisticas['monto_ventas_mes'], 2) ?></div>
                <div class="stat-label">Ventas del Mes</div>
                <div class="stat-detail">
                    <?= number_format($estadisticas['ventas_mes']) ?> transacciones
                    <?php if ($estadisticas['ventas_hoy'] > 0): ?>
                        <span style="color: var(--success); font-weight: 600;"> • <?= $estadisticas['ventas_hoy'] ?> hoy</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Valor Inventario -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon success">
                    <i class="fas fa-warehouse"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= MONEDA_SIMBOLO ?> <?= number_format($estadisticas['valor_inventario'], 2) ?></div>
                <div class="stat-label">Valor del Inventario</div>
                <div class="stat-detail"><?= number_format($estadisticas['total_stock']) ?> unidades en stock</div>
            </div>
        </div>

        <!-- Productos -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon info">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= number_format($estadisticas['total_productos']) ?></div>
                <div class="stat-label">Productos Registrados</div>
                <div class="stat-detail"><?= $estadisticas['productos_stock_bajo'] ?> con stock bajo</div>
            </div>
        </div>

        <!-- Alertas -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon <?= $resumen_alertas['total'] > 0 ? 'warning' : 'secondary' ?>">
                    <i class="fas fa-bell"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $resumen_alertas['total'] ?></div>
                <div class="stat-label">Alertas Activas</div>
                <div class="stat-detail">
                    <?php if ($resumen_alertas['total'] > 0): ?>
                        Requieren atención
                    <?php else: ?>
                        Todo en orden
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas Críticas -->
    <?php if (!empty($alertas)): ?>
    <div class="section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-exclamation-triangle"></i>
                Productos que Requieren Atención
            </h2>
            <span class="badge badge-warning"><?= count($alertas) ?> alertas</span>
        </div>

        <div class="alerts-grid">
            <?php foreach ($alertas as $alerta): ?>
            <div class="alert-card alert-<?= $alerta['tipo_alerta'] ?>">
                <div class="alert-icon">
                    <?php if ($alerta['tipo_alerta'] === 'producto_agotado'): ?>
                        <i class="fas fa-times-circle"></i>
                    <?php elseif ($alerta['tipo_alerta'] === 'stock_critico'): ?>
                        <i class="fas fa-exclamation-triangle"></i>
                    <?php else: ?>
                        <i class="fas fa-exclamation-circle"></i>
                    <?php endif; ?>
                </div>
                <div class="alert-body">
                    <div class="alert-title"><?= htmlspecialchars($alerta['producto_nombre']) ?></div>
                    <div class="alert-message"><?= htmlspecialchars($alerta['mensaje']) ?></div>
                    <div class="alert-meta">
                        <span>Stock: <?= $alerta['stock_actual'] ?></span>
                        <span>•</span>
                        <span>Mínimo: <?= $alerta['stock_minimo'] ?></span>
                    </div>
                </div>
                <div class="alert-action">
                    <a href="<?= APP_URL ?>/compra/nueva" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Comprar
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Dos Columnas -->
    <div class="dashboard-grid">
        <!-- Productos con Stock Bajo -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-box-open"></i>
                    Productos con Stock Bajo
                </h2>
            </div>

            <?php if (!empty($productos_stock_bajo)): ?>
            <div class="table-card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Producto</th>
                                <th style="text-align: center;">Stock Actual</th>
                                <th style="text-align: center;">Stock Mínimo</th>
                                <th style="text-align: center;">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($productos_stock_bajo, 0, 10) as $producto): ?>
                            <tr>
                                <td><code><?= htmlspecialchars($producto['codigo']) ?></code></td>
                                <td><strong><?= htmlspecialchars($producto['nombre']) ?></strong></td>
                                <td style="text-align: center;">
                                    <span class="badge badge-<?= $producto['stock_actual'] == 0 ? 'danger' : 'warning' ?>">
                                        <?= $producto['stock_actual'] ?>
                                    </span>
                                </td>
                                <td style="text-align: center;"><?= $producto['stock_minimo'] ?></td>
                                <td style="text-align: center;">
                                    <span class="badge badge-<?= strtolower($producto['nivel_alerta']) ?>">
                                        <?= $producto['nivel_alerta'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (count($productos_stock_bajo) > 10): ?>
                <div class="table-footer">
                    <a href="<?= APP_URL ?>/reporte/stock" class="btn btn-sm btn-secondary">
                        Ver todos (<?= count($productos_stock_bajo) ?>)
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div class="empty-state-small">
                <i class="fas fa-check-circle"></i>
                <p>Todos los productos tienen stock adecuado</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Ventas Recientes -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-history"></i>
                    Últimas Ventas
                </h2>
            </div>

            <?php if (!empty($ventas_recientes)): ?>
            <div class="table-card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Cliente</th>
                                <th style="text-align: right;">Total</th>
                                <th style="text-align: right;">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ventas_recientes as $venta): ?>
                            <tr>
                                <td>
                                    <a href="<?= APP_URL ?>/venta/ver?id=<?= $venta['id'] ?>" class="link-primary">
                                        <strong><?= htmlspecialchars($venta['numero_venta']) ?></strong>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($venta['cliente_nombre']) ?></td>
                                <td style="text-align: right;">
                                    <strong><?= MONEDA_SIMBOLO ?> <?= number_format($venta['total'], 2) ?></strong>
                                </td>
                                <td style="text-align: right;">
                                    <small><?= date('d/m/Y H:i', strtotime($venta['fecha_venta'])) ?></small>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php else: ?>
            <div class="empty-state-small">
                <i class="fas fa-shopping-cart"></i>
                <p>No hay ventas registradas hoy</p>
            </div>
            <?php endif; ?>

            <div class="section-footer">
                <a href="<?= APP_URL ?>/venta/nueva" class="btn btn-primary btn-block">
                    <i class="fas fa-plus"></i> Nueva Venta
                </a>
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos -->
    <div class="section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-bolt"></i>
                Accesos Rápidos
            </h2>
        </div>
        <div class="quick-actions">
            <a href="<?= APP_URL ?>/venta/nueva" class="quick-action-card primary">
                <i class="fas fa-cash-register"></i>
                <span>Punto de Venta</span>
            </a>
            <a href="<?= APP_URL ?>/compra/nueva" class="quick-action-card success">
                <i class="fas fa-truck-loading"></i>
                <span>Nueva Compra</span>
            </a>
            <a href="<?= APP_URL ?>/producto/crear" class="quick-action-card info">
                <i class="fas fa-plus-circle"></i>
                <span>Nuevo Producto</span>
            </a>
            <a href="<?= APP_URL ?>/reporte/ventas" class="quick-action-card warning">
                <i class="fas fa-chart-line"></i>
                <span>Reporte Ventas</span>
            </a>
        </div>
    </div>
</div>

<style>
.dashboard {
    max-width: 1400px;
    margin: 0 auto;
}

.page-subtitle {
    color: var(--text-light);
    font-size: 14px;
    margin-top: 4px;
}

/* Estadísticas */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 24px;
    transition: all 0.3s;
}

.stat-card:hover {
    box-shadow: 0 4px 12px var(--shadow-md);
    transform: translateY(-2px);
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-icon.primary {
    background: rgba(14, 165, 233, 0.1);
    color: var(--primary);
}

.stat-icon.success {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}

.stat-icon.info {
    background: rgba(99, 102, 241, 0.1);
    color: #6366f1;
}

.stat-icon.warning {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
}

.stat-icon.secondary {
    background: rgba(107, 114, 128, 0.1);
    color: var(--text-light);
}

.stat-trend {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--text-light);
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--dark-text);
    margin-bottom: 4px;
}

.stat-label {
    font-size: 13px;
    color: var(--text-light);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-detail {
    font-size: 13px;
    color: var(--text);
}

/* Secciones */
.section {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border-light);
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--dark-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-footer {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--border-light);
}

/* Alertas Grid */
.alerts-grid {
    display: grid;
    gap: 12px;
}

.alert-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    border-radius: 8px;
    border-left: 4px solid;
}

.alert-card.alert-producto_agotado {
    background: #fef2f2;
    border-left-color: #dc2626;
}

.alert-card.alert-stock_critico {
    background: #fef3c7;
    border-left-color: #f59e0b;
}

.alert-card.alert-stock_bajo {
    background: #fef9c3;
    border-left-color: #eab308;
}

.alert-card .alert-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.alert-card.alert-producto_agotado .alert-icon {
    color: #dc2626;
}

.alert-card.alert-stock_critico .alert-icon {
    color: #f59e0b;
}

.alert-card.alert-stock_bajo .alert-icon {
    color: #eab308;
}

.alert-body {
    flex: 1;
    min-width: 0;
}

.alert-title {
    font-weight: 600;
    color: var(--dark-text);
    margin-bottom: 4px;
}

.alert-message {
    font-size: 13px;
    color: var(--text);
    margin-bottom: 4px;
}

.alert-meta {
    font-size: 12px;
    color: var(--text-light);
    display: flex;
    gap: 8px;
}

.alert-action {
    flex-shrink: 0;
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 24px;
    margin-bottom: 24px;
}

/* Empty State */
.empty-state-small {
    text-align: center;
    padding: 40px 20px;
    color: var(--text-light);
}

.empty-state-small i {
    font-size: 48px;
    margin-bottom: 12px;
    opacity: 0.3;
}

.empty-state-small p {
    margin: 0;
    font-size: 14px;
}

/* Table Card */
.table-card {
    border: 1px solid var(--border-light);
    border-radius: 8px;
    overflow: hidden;
}

.table-footer {
    padding: 12px;
    background: var(--light-bg);
    text-align: center;
    border-top: 1px solid var(--border-light);
}

/* Accesos Rápidos */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.quick-action-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    padding: 24px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.quick-action-card i {
    font-size: 32px;
}

.quick-action-card span {
    font-weight: 600;
    font-size: 14px;
}

.quick-action-card.primary {
    background: rgba(14, 165, 233, 0.1);
    color: var(--primary);
}

.quick-action-card.primary:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
}

.quick-action-card.success {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}

.quick-action-card.success:hover {
    background: var(--success);
    color: white;
    transform: translateY(-2px);
}

.quick-action-card.info {
    background: rgba(99, 102, 241, 0.1);
    color: #6366f1;
}

.quick-action-card.info:hover {
    background: #6366f1;
    color: white;
    transform: translateY(-2px);
}

.quick-action-card.warning {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
}

.quick-action-card.warning:hover {
    background: var(--warning);
    color: white;
    transform: translateY(-2px);
}

.link-primary {
    color: var(--primary);
    text-decoration: none;
}

.link-primary:hover {
    text-decoration: underline;
}

code {
    background: var(--light-bg);
    padding: 2px 6px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 12px;
    color: var(--dark-text);
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .dashboard-grid {
        grid-template-columns: 1fr;
    }

    .quick-actions {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
