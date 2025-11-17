<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-truck"></i>
        Gestión de Proveedores
    </h1>
    <div class="page-actions">
        <a href="<?= APP_URL ?>/proveedor/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Proveedor
        </a>
    </div>
</div>

<!-- Tabla de proveedores -->
<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($proveedores)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        No hay proveedores registrados
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($proveedores as $proveedor): ?>
                    <tr>
                        <td><?= $proveedor['id'] ?></td>
                        <td><strong><?= htmlspecialchars($proveedor['nombre']) ?></strong></td>
                        <td><?= htmlspecialchars($proveedor['contacto'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($proveedor['telefono'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($proveedor['email'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($proveedor['direccion'] ?? '-') ?></td>
                        <td><?= date('d/m/Y', strtotime($proveedor['fecha_registro'])) ?></td>
                        <td class="actions">
                            <a href="<?= APP_URL ?>/proveedor/editar?id=<?= $proveedor['id'] ?>"
                               class="btn btn-sm btn-info" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= APP_URL ?>/proveedor/eliminar?id=<?= $proveedor['id'] ?>"
                               class="btn btn-sm btn-danger" title="Desactivar"
                               onclick="return confirm('¿Está seguro de desactivar este proveedor?')">
                                <i class="fas fa-ban"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
