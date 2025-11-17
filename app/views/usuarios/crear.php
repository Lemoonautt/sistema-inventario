<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-user-plus"></i>
        Nuevo Usuario
    </h1>
</div>

<div class="form-card">
    <form method="POST" action="<?= APP_URL ?>/usuario/guardar">
        <div class="form-grid">
            <div class="form-section">
                <h3>Información de Acceso</h3>

                <div class="form-group">
                    <label for="username">Usuario <span class="required">*</span></label>
                    <input type="text" id="username" name="username" class="form-control" required>
                    <small class="form-text">Nombre de usuario para iniciar sesión</small>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña <span class="required">*</span></label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <small class="form-text">Mínimo 6 caracteres</small>
                </div>

                <div class="form-group">
                    <label for="rol">Rol <span class="required">*</span></label>
                    <select id="rol" name="rol" class="form-control" required>
                        <option value="vendedor">Vendedor</option>
                        <option value="almacenero">Almacenero</option>
                        <option value="administrador">Administrador</option>
                    </select>
                    <small class="form-text">Nivel de acceso del usuario</small>
                </div>

                <div class="form-group">
                    <label for="activo">Estado</label>
                    <select id="activo" name="activo" class="form-control">
                        <option value="1" selected>Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="form-section">
                <h3>Información Personal</h3>

                <div class="form-group">
                    <label for="nombre_completo">Nombre Completo <span class="required">*</span></label>
                    <input type="text" id="nombre_completo" name="nombre_completo" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar Usuario
            </button>
            <a href="<?= APP_URL ?>/usuario" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>
