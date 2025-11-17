<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-box"></i>
        Gestión de Productos
    </h1>
    <div class="page-actions">
        <a href="<?= APP_URL ?>/producto/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Producto
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="filters-card">
    <form method="GET" action="<?= APP_URL ?>/producto" class="filters-form">
        <div class="form-row">
            <div class="form-group">
                <label>Buscar:</label>
                <input type="text" name="busqueda" class="form-control"
                       placeholder="Nombre o código..."
                       value="<?= htmlspecialchars($filtros['busqueda']) ?>">
            </div>

            <div class="form-group">
                <label>Categoría:</label>
                <select name="categoria_id" class="form-control">
                    <option value="">Todas</option>
                    <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>"
                            <?= $filtros['categoria_id'] == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                <a href="<?= APP_URL ?>/producto" class="btn btn-light">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Tabla de productos -->
<div class="table-card">
    <?php if (!empty($productos)): ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $prod): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($prod['codigo']) ?></strong></td>
                    <td><?= htmlspecialchars($prod['nombre']) ?></td>
                    <td><?= htmlspecialchars($prod['categoria_nombre'] ?? 'Sin categoría') ?></td>
                    <td><?= MONEDA_SIMBOLO ?> <?= number_format($prod['precio_compra'], 2) ?></td>
                    <td><?= MONEDA_SIMBOLO ?> <?= number_format($prod['precio_venta'], 2) ?></td>
                    <td>
                        <span class="stock-badge
                            <?= $prod['stock_actual'] == 0 ? 'stock-agotado' :
                                ($prod['stock_actual'] <= $prod['stock_minimo'] ? 'stock-bajo' : 'stock-normal') ?>">
                            <?= $prod['stock_actual'] ?>
                        </span>
                        <small class="text-muted">/ <?= $prod['stock_minimo'] ?></small>
                    </td>
                    <td>
                        <?php if ($prod['stock_actual'] == 0): ?>
                            <span class="badge badge-danger">AGOTADO</span>
                        <?php elseif ($prod['stock_actual'] <= $prod['stock_minimo']): ?>
                            <span class="badge badge-warning">BAJO</span>
                        <?php else: ?>
                            <span class="badge badge-success">OK</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="<?= APP_URL ?>/producto/editar?id=<?= $prod['id'] ?>"
                           class="btn btn-sm btn-info" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="<?= APP_URL ?>/producto/eliminar?id=<?= $prod['id'] ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('¿Eliminar este producto?')"
                           title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <p>Total de productos: <strong><?= count($productos) ?></strong></p>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <p>No hay productos registrados</p>
        <a href="<?= APP_URL ?>/producto/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear primer producto
        </a>
    </div>
    <?php endif; ?>
</div>
