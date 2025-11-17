<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-truck"></i>
        Gestión de Compras (Entradas al Sistema)
    </h1>
    <div class="page-actions">
        <a href="<?= APP_URL ?>/compra/nueva" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Compra
        </a>
    </div>
</div>

<div class="table-card">
    <?php if (!empty($compras)): ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nº Compra</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($compras as $compra): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($compra['numero_compra']) ?></strong></td>
                    <td><?= htmlspecialchars($compra['proveedor_nombre']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($compra['fecha_compra'])) ?></td>
                    <td><?= MONEDA_SIMBOLO ?> <?= number_format($compra['total'], 2) ?></td>
                    <td>
                        <a href="<?= APP_URL ?>/compra/ver?id=<?= $compra['id'] ?>"
                           class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-truck"></i>
        <p>No hay compras registradas</p>
    </div>
    <?php endif; ?>
</div>
