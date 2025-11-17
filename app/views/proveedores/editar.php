<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-truck"></i>
        Editar Proveedor
    </h1>
</div>

<div class="form-card">
    <form method="POST" action="<?= APP_URL ?>/proveedor/actualizar">
        <input type="hidden" name="id" value="<?= $proveedor['id'] ?>">

        <div class="form-grid">
            <div class="form-section">
                <h3>Información Básica</h3>

                <div class="form-group">
                    <label for="nombre">Nombre del Proveedor <span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre" class="form-control"
                           value="<?= htmlspecialchars($proveedor['nombre']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="contacto">Persona de Contacto</label>
                    <input type="text" id="contacto" name="contacto" class="form-control"
                           value="<?= htmlspecialchars($proveedor['contacto'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Fecha de Registro</label>
                    <input type="text" class="form-control"
                           value="<?= date('d/m/Y H:i', strtotime($proveedor['fecha_registro'])) ?>"
                           readonly disabled>
                </div>
            </div>

            <div class="form-section">
                <h3>Información de Contacto</h3>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" class="form-control"
                           value="<?= htmlspecialchars($proveedor['telefono'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($proveedor['email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <textarea id="direccion" name="direccion" class="form-control" rows="3"><?= htmlspecialchars($proveedor['direccion'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar Proveedor
            </button>
            <a href="<?= APP_URL ?>/proveedor" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>
