<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-users"></i>
        Gestión de Usuarios
    </h1>
    <div class="page-actions">
        <a href="<?= APP_URL ?>/usuario/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="filters-card">
    <form method="GET" action="<?= APP_URL ?>/usuario" class="filters-form">
        <div class="form-group">
            <input type="text" name="busqueda" class="form-control"
                   placeholder="Buscar por usuario, nombre o email..."
                   value="<?= htmlspecialchars($filtros['busqueda']) ?>">
        </div>
        <div class="form-group">
            <select name="rol" class="form-control">
                <option value="">Todos los roles</option>
                <option value="administrador" <?= $filtros['rol'] == 'administrador' ? 'selected' : '' ?>>
                    Administrador
                </option>
                <option value="vendedor" <?= $filtros['rol'] == 'vendedor' ? 'selected' : '' ?>>
                    Vendedor
                </option>
                <option value="almacenero" <?= $filtros['rol'] == 'almacenero' ? 'selected' : '' ?>>
                    Almacenero
                </option>
            </select>
        </div>
        <div class="form-group">
            <select name="activo" class="form-control">
                <option value="">Todos los estados</option>
                <option value="1" <?= $filtros['activo'] === '1' ? 'selected' : '' ?>>Activos</option>
                <option value="0" <?= $filtros['activo'] === '0' ? 'selected' : '' ?>>Inactivos</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary">
            <i class="fas fa-search"></i> Filtrar
        </button>
        <a href="<?= APP_URL ?>/usuario" class="btn btn-light">
            <i class="fas fa-eraser"></i> Limpiar
        </a>
    </form>
</div>

<!-- Tabla de usuarios -->
<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                    <th>Último Acceso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        No se encontraron usuarios
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($usuario['username']) ?></strong></td>
                        <td><?= htmlspecialchars($usuario['nombre_completo']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td>
                            <?php
                            $roles = [
                                'administrador' => '<span class="badge badge-danger">Administrador</span>',
                                'vendedor' => '<span class="badge badge-info">Vendedor</span>',
                                'almacenero' => '<span class="badge badge-warning">Almacenero</span>'
                            ];
                            echo $roles[$usuario['rol']] ?? $usuario['rol'];
                            ?>
                        </td>
                        <td>
                            <?php if ($usuario['activo']): ?>
                                <span class="badge badge-success">Activo</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($usuario['fecha_registro'])) ?></td>
                        <td>
                            <?= $usuario['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($usuario['ultimo_acceso'])) : '-' ?>
                        </td>
                        <td class="actions">
                            <a href="<?= APP_URL ?>/usuario/editar?id=<?= $usuario['id'] ?>"
                               class="btn btn-sm btn-info" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if ($usuario['activo']): ?>
                            <a href="<?= APP_URL ?>/usuario/eliminar?id=<?= $usuario['id'] ?>"
                               class="btn btn-sm btn-danger" title="Desactivar"
                               onclick="return confirm('¿Está seguro de desactivar este usuario?')">
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
