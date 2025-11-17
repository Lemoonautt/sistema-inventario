<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-shopping-cart"></i>
        Gestión de Ventas (Salidas del Sistema)
    </h1>
    <div class="page-actions">
        <a href="<?= APP_URL ?>/venta/nueva" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Venta
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="filters-card">
    <form method="GET" action="<?= APP_URL ?>/venta" class="filters-form">
        <div class="form-row">
            <div class="form-group">
                <label>Desde:</label>
                <input type="date" name="fecha_desde" class="form-control"
                       value="<?= $filtros['fecha_desde'] ?>">
            </div>

            <div class="form-group">
                <label>Hasta:</label>
                <input type="date" name="fecha_hasta" class="form-control"
                       value="<?= $filtros['fecha_hasta'] ?>">
            </div>

            <div class="form-group">
                <label>Estado:</label>
                <select name="estado" class="form-control">
                    <option value="">Todos</option>
                    <option value="completada" <?= $filtros['estado'] == 'completada' ? 'selected' : '' ?>>
                        Completada
                    </option>
                    <option value="cancelada" <?= $filtros['estado'] == 'cancelada' ? 'selected' : '' ?>>
                        Cancelada
                    </option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-search"></i> Filtrar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Tabla de ventas -->
<div class="table-card">
    <?php if (!empty($ventas)): ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nº Venta</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Tipo Pago</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalGeneral = 0;
                foreach ($ventas as $venta):
                    $totalGeneral += $venta['total'];
                ?>
                <tr>
                    <td><strong><?= htmlspecialchars($venta['numero_venta']) ?></strong></td>
                    <td><?= htmlspecialchars($venta['cliente_nombre']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($venta['fecha_venta'])) ?></td>
                    <td><?= MONEDA_SIMBOLO ?> <?= number_format($venta['total'], 2) ?></td>
                    <td>
                        <span class="badge badge-light"><?= ucfirst($venta['tipo_pago']) ?></span>
                    </td>
                    <td>
                        <?php if ($venta['estado'] == 'completada'): ?>
                            <span class="badge badge-success">Completada</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Cancelada</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="<?= APP_URL ?>/venta/ver?id=<?= $venta['id'] ?>"
                           class="btn btn-sm btn-info" title="Ver">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-total">
                    <td colspan="3"><strong>TOTAL:</strong></td>
                    <td colspan="4">
                        <strong><?= MONEDA_SIMBOLO ?> <?= number_format($totalGeneral, 2) ?></strong>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-shopping-cart"></i>
        <p>No hay ventas registradas</p>
        <a href="<?= APP_URL ?>/venta/nueva" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Venta
        </a>
    </div>
    <?php endif; ?>
</div>
