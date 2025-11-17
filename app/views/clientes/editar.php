<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-user-edit"></i>
        Editar Cliente
    </h1>
</div>

<div class="form-card">
    <form method="POST" action="<?= APP_URL ?>/cliente/actualizar">
        <input type="hidden" name="id" value="<?= $cliente['id'] ?>">

        <div class="form-grid">
            <div class="form-section">
                <h3>Información Básica</h3>

                <div class="form-group">
                    <label for="nombre">Nombre del Cliente <span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre" class="form-control"
                           value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="nit">NIT / CI</label>
                    <input type="text" id="nit" name="nit" class="form-control"
                           value="<?= htmlspecialchars($cliente['nit'] ?? '0') ?>">
                    <small class="form-text">Número de Identificación Tributaria o Cédula de Identidad</small>
                </div>

                <div class="form-group">
                    <label>Fecha de Registro</label>
                    <input type="text" class="form-control"
                           value="<?= date('d/m/Y H:i', strtotime($cliente['fecha_registro'])) ?>"
                           readonly disabled>
                </div>
            </div>

            <div class="form-section">
                <h3>Información de Contacto</h3>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" class="form-control"
                           value="<?= htmlspecialchars($cliente['telefono'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($cliente['email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <textarea id="direccion" name="direccion" class="form-control" rows="3"><?= htmlspecialchars($cliente['direccion'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar Cliente
            </button>
            <a href="<?= APP_URL ?>/cliente" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>
