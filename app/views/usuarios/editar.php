<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-user-edit"></i>
        Editar Usuario
    </h1>
</div>

<div class="form-card">
    <form method="POST" action="<?= APP_URL ?>/usuario/actualizar">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

        <div class="form-grid">
            <div class="form-section">
                <h3>Información de Acceso</h3>

                <div class="form-group">
                    <label for="username">Usuario <span class="required">*</span></label>
                    <input type="text" id="username" name="username" class="form-control"
                           value="<?= htmlspecialchars($usuario['username']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Nueva Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control">
                    <small class="form-text">Dejar en blanco para mantener la contraseña actual</small>
                </div>

                <div class="form-group">
                    <label for="rol">Rol <span class="required">*</span></label>
                    <select id="rol" name="rol" class="form-control" required>
                        <option value="vendedor" <?= $usuario['rol'] == 'vendedor' ? 'selected' : '' ?>>
                            Vendedor
                        </option>
                        <option value="almacenero" <?= $usuario['rol'] == 'almacenero' ? 'selected' : '' ?>>
                            Almacenero
                        </option>
                        <option value="administrador" <?= $usuario['rol'] == 'administrador' ? 'selected' : '' ?>>
                            Administrador
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="activo">Estado</label>
                    <select id="activo" name="activo" class="form-control">
                        <option value="1" <?= $usuario['activo'] ? 'selected' : '' ?>>Activo</option>
                        <option value="0" <?= !$usuario['activo'] ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="form-section">
                <h3>Información Personal</h3>

                <div class="form-group">
                    <label for="nombre_completo">Nombre Completo <span class="required">*</span></label>
                    <input type="text" id="nombre_completo" name="nombre_completo" class="form-control"
                           value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($usuario['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Fecha de Registro</label>
                    <input type="text" class="form-control"
                           value="<?= date('d/m/Y H:i', strtotime($usuario['fecha_registro'])) ?>"
                           readonly disabled>
                </div>

                <div class="form-group">
                    <label>Último Acceso</label>
                    <input type="text" class="form-control"
                           value="<?= $usuario['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($usuario['ultimo_acceso'])) : 'Nunca' ?>"
                           readonly disabled>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar Usuario
            </button>
            <a href="<?= APP_URL ?>/usuario" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>
