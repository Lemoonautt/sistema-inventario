<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-users-cog"></i>
        Gestión de Clientes
    </h1>
    <div class="page-actions">
        <a href="<?= APP_URL ?>/cliente/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Cliente
        </a>
    </div>
</div>

<!-- Tabla de clientes -->
<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>NIT</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clientes)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        No hay clientes registrados
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= $cliente['id'] ?></td>
                        <td><strong><?= htmlspecialchars($cliente['nombre']) ?></strong></td>
                        <td><?= htmlspecialchars($cliente['nit'] ?? '0') ?></td>
                        <td><?= htmlspecialchars($cliente['telefono'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($cliente['email'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($cliente['direccion'] ?? '-') ?></td>
                        <td><?= date('d/m/Y', strtotime($cliente['fecha_registro'])) ?></td>
                        <td class="actions">
                            <a href="<?= APP_URL ?>/cliente/editar?id=<?= $cliente['id'] ?>"
                               class="btn btn-sm btn-info" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if ($cliente['id'] != 1): // No permitir eliminar cliente general ?>
                            <a href="<?= APP_URL ?>/cliente/eliminar?id=<?= $cliente['id'] ?>"
                               class="btn btn-sm btn-danger" title="Desactivar"
                               onclick="return confirm('¿Está seguro de desactivar este cliente?')">
                                <i class="fas fa-ban"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
