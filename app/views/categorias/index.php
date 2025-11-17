<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-tags"></i>
        Gestión de Categorías
    </h1>
    <div class="page-actions">
        <a href="<?= APP_URL ?>/categoria/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Categoría
        </a>
    </div>
</div>

<?php if (empty($categorias)): ?>
    <div class="empty-state">
        <i class="fas fa-tags"></i>
        <h3>No hay categorías registradas</h3>
        <p>Comienza creando tu primera categoría para organizar tus productos</p>
        <a href="<?= APP_URL ?>/categoria/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Primera Categoría
        </a>
    </div>
<?php else: ?>
    <div class="table-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th style="text-align: center;">Productos</th>
                        <th style="text-align: center;">Stock Total</th>
                        <th style="text-align: right;">Valor Inventario</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= $categoria['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($categoria['nombre']) ?></strong>
                        </td>
                        <td>
                            <?php if (!empty($categoria['descripcion'])): ?>
                                <span class="text-muted"><?= htmlspecialchars($categoria['descripcion']) ?></span>
                            <?php else: ?>
                                <span class="text-muted-light">Sin descripción</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <?php if ($categoria['total_productos'] > 0): ?>
                                <span class="badge badge-primary"><?= $categoria['total_productos'] ?></span>
                            <?php else: ?>
                                <span class="badge badge-secondary">0</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <?= number_format($categoria['total_stock']) ?>
                        </td>
                        <td style="text-align: right;">
                            <strong><?= MONEDA_SIMBOLO ?> <?= number_format($categoria['valor_inventario'], 2) ?></strong>
                        </td>
                        <td style="text-align: center;">
                            <div class="btn-group">
                                <a href="<?= APP_URL ?>/categoria/editar?id=<?= $categoria['id'] ?>"
                                   class="btn btn-sm btn-secondary"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= APP_URL ?>/categoria/eliminar?id=<?= $categoria['id'] ?>"
                                   class="btn btn-sm btn-danger"
                                   data-confirm="¿Está seguro de eliminar esta categoría?"
                                   title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Resumen -->
    <div class="summary-card">
        <div class="summary-item">
            <i class="fas fa-tags"></i>
            <div class="summary-content">
                <span class="summary-label">Total Categorías</span>
                <span class="summary-value"><?= count($categorias) ?></span>
            </div>
        </div>
        <div class="summary-item">
            <i class="fas fa-box"></i>
            <div class="summary-content">
                <span class="summary-label">Total Productos</span>
                <span class="summary-value"><?= array_sum(array_column($categorias, 'total_productos')) ?></span>
            </div>
        </div>
        <div class="summary-item">
            <i class="fas fa-cubes"></i>
            <div class="summary-content">
                <span class="summary-label">Total Stock</span>
                <span class="summary-value"><?= number_format(array_sum(array_column($categorias, 'total_stock'))) ?></span>
            </div>
        </div>
        <div class="summary-item">
            <i class="fas fa-dollar-sign"></i>
            <div class="summary-content">
                <span class="summary-label">Valor Total Inventario</span>
                <span class="summary-value"><?= MONEDA_SIMBOLO ?> <?= number_format(array_sum(array_column($categorias, 'valor_inventario')), 2) ?></span>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
.text-muted {
    color: var(--text);
    font-size: 14px;
}

.text-muted-light {
    color: var(--text-light);
    font-size: 14px;
    font-style: italic;
}

.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.badge-primary {
    background: var(--primary);
    color: white;
}

.badge-secondary {
    background: var(--text-light);
    color: white;
}

.btn-group {
    display: flex;
    gap: 4px;
    justify-content: center;
}

.summary-card {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    margin-top: 24px;
}

.summary-item {
    background: white;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.summary-item i {
    width: 48px;
    height: 48px;
    background: var(--primary-light);
    color: var(--primary);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.summary-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.summary-label {
    font-size: 12px;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.summary-value {
    font-size: 24px;
    font-weight: 700;
    color: var(--dark-text);
}

@media (max-width: 768px) {
    .summary-card {
        grid-template-columns: 1fr;
    }
}
</style>
