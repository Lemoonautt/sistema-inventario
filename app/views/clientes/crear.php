<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-user-plus"></i>
        Nuevo Cliente
    </h1>
</div>

<div class="form-card">
    <form method="POST" action="<?= APP_URL ?>/cliente/guardar">
        <div class="form-grid">
            <div class="form-section">
                <h3>Información Básica</h3>

                <div class="form-group">
                    <label for="nombre">Nombre del Cliente <span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="nit">NIT / CI</label>
                    <input type="text" id="nit" name="nit" class="form-control" placeholder="0">
                    <small class="form-text">Número de Identificación Tributaria o Cédula de Identidad</small>
                </div>
            </div>

            <div class="form-section">
                <h3>Información de Contacto</h3>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" class="form-control">
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control">
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <textarea id="direccion" name="direccion" class="form-control" rows="3"></textarea>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar Cliente
            </button>
            <a href="<?= APP_URL ?>/cliente" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>
