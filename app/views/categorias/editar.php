<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-edit"></i>
        Editar Categoría
    </h1>
</div>

<div class="form-card">
    <form method="POST" action="<?= APP_URL ?>/categoria/actualizar" id="formCategoria">
        <input type="hidden" name="id" value="<?= $categoria['id'] ?>">

        <div class="form-group">
            <label for="nombre">Nombre de la Categoría <span class="required">*</span></label>
            <input type="text"
                   id="nombre"
                   name="nombre"
                   class="form-control"
                   value="<?= htmlspecialchars($categoria['nombre']) ?>"
                   required
                   autofocus
                   maxlength="50">
            <small class="form-text">Máximo 50 caracteres</small>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción (opcional)</label>
            <textarea id="descripcion"
                      name="descripcion"
                      class="form-control"
                      rows="3"
                      maxlength="255"><?= htmlspecialchars($categoria['descripcion'] ?? '') ?></textarea>
            <small class="form-text">Máximo 255 caracteres</small>
        </div>

        <!-- Estadísticas de la categoría -->
        <?php if ($stats['total_productos'] > 0): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <div>
                Esta categoría tiene <strong><?= $stats['total_productos'] ?> producto(s)</strong> asociado(s),
                con un stock total de <strong><?= number_format($stats['total_stock']) ?> unidades</strong>
                y un valor de inventario de <strong><?= MONEDA_SIMBOLO ?> <?= number_format($stats['valor_inventario'], 2) ?></strong>.
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                Esta categoría aún no tiene productos asociados. Puedes asignar productos a esta categoría al crear o editar un producto.
            </div>
        </div>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar Categoría
            </button>
            <a href="<?= APP_URL ?>/categoria" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<style>
.alert {
    display: flex;
    gap: 12px;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 24px;
    border: 1px solid;
}

.alert i {
    font-size: 20px;
    flex-shrink: 0;
    margin-top: 2px;
}

.alert-info {
    background: #e0f2fe;
    border-color: #7dd3fc;
    color: #0c4a6e;
}

.alert-info i {
    color: #0284c7;
}

.alert-warning {
    background: #fef3c7;
    border-color: #fcd34d;
    color: #78350f;
}

.alert-warning i {
    color: #d97706;
}

.alert div {
    flex: 1;
    font-size: 14px;
    line-height: 1.5;
}

.alert strong {
    font-weight: 600;
}
</style>

<script>
// Validación del formulario
document.getElementById('formCategoria').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value.trim();

    if (nombre === '') {
        e.preventDefault();
        alert('El nombre de la categoría es obligatorio');
        document.getElementById('nombre').focus();
        return false;
    }

    if (nombre.length > 50) {
        e.preventDefault();
        alert('El nombre no puede exceder los 50 caracteres');
        document.getElementById('nombre').focus();
        return false;
    }
});

// Contador de caracteres para descripción
const descripcionTextarea = document.getElementById('descripcion');
const maxLength = 255;

descripcionTextarea.addEventListener('input', function() {
    const remaining = maxLength - this.value.length;
    const formText = this.nextElementSibling;

    if (remaining < 50) {
        formText.textContent = `${remaining} caracteres restantes`;
        if (remaining < 20) {
            formText.style.color = '#dc2626';
        } else {
            formText.style.color = '#ea580c';
        }
    } else {
        formText.textContent = 'Máximo 255 caracteres';
        formText.style.color = '';
    }
});
</script>
