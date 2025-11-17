<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-plus-circle"></i>
        Nueva Categoría
    </h1>
</div>

<div class="form-card">
    <form method="POST" action="<?= APP_URL ?>/categoria/guardar" id="formCategoria">
        <div class="form-group">
            <label for="nombre">Nombre de la Categoría <span class="required">*</span></label>
            <input type="text"
                   id="nombre"
                   name="nombre"
                   class="form-control"
                   placeholder="Ej: Herramientas, Electrónica, etc."
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
                      placeholder="Describe brevemente esta categoría..."
                      maxlength="255"></textarea>
            <small class="form-text">Máximo 255 caracteres</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Crear Categoría
            </button>
            <a href="<?= APP_URL ?>/categoria" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

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
