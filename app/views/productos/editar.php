<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-edit"></i>
        Editar Producto
    </h1>
    <div class="page-actions">
        <a href="<?= APP_URL ?>/producto" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="<?= APP_URL ?>/producto/actualizar">
        <input type="hidden" name="id" value="<?= $producto['id'] ?>">

        <div class="form-grid">
            <!-- Información básica -->
            <div class="form-section">
                <h3>Información Básica</h3>

                <div class="form-group">
                    <label for="codigo">Código <span class="required">*</span></label>
                    <input type="text" id="codigo" name="codigo" class="form-control"
                           required value="<?= htmlspecialchars($producto['codigo']) ?>">
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre del Producto <span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre" class="form-control"
                           required value="<?= htmlspecialchars($producto['nombre']) ?>">
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-control"
                              rows="3"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="categoria_id">Categoría</label>
                    <select id="categoria_id" name="categoria_id" class="form-control">
                        <option value="">Sin categoría</option>
                        <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>"
                                <?= $producto['categoria_id'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Stock Actual</label>
                    <div class="stock-display">
                        <span class="stock-value"><?= $producto['stock_actual'] ?></span>
                        <span class="stock-unit"><?= $producto['unidad_medida'] ?></span>
                    </div>
                    <small class="form-text">
                        El stock se actualiza automáticamente con compras y ventas
                    </small>
                </div>
            </div>

            <!-- Precios -->
            <div class="form-section">
                <h3>Precios y Configuración</h3>

                <div class="form-group">
                    <label for="precio_compra">Precio de Compra <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-prefix"><?= MONEDA_SIMBOLO ?></span>
                        <input type="number" id="precio_compra" name="precio_compra"
                               class="form-control" step="0.01" min="0" required
                               value="<?= $producto['precio_compra'] ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="precio_venta">Precio de Venta <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-prefix"><?= MONEDA_SIMBOLO ?></span>
                        <input type="number" id="precio_venta" name="precio_venta"
                               class="form-control" step="0.01" min="0" required
                               value="<?= $producto['precio_venta'] ?>">
                    </div>
                    <small id="margen" class="form-text"></small>
                </div>

                <div class="form-group">
                    <label for="stock_minimo">Stock Mínimo <span class="required">*</span></label>
                    <input type="number" id="stock_minimo" name="stock_minimo"
                           class="form-control" min="0" required
                           value="<?= $producto['stock_minimo'] ?>">
                </div>

                <div class="form-group">
                    <label for="stock_maximo">Stock Máximo</label>
                    <input type="number" id="stock_maximo" name="stock_maximo"
                           class="form-control" min="0"
                           value="<?= $producto['stock_maximo'] ?>">
                </div>

                <div class="form-group">
                    <label for="unidad_medida">Unidad de Medida</label>
                    <select id="unidad_medida" name="unidad_medida" class="form-control">
                        <option value="unidad" <?= $producto['unidad_medida'] == 'unidad' ? 'selected' : '' ?>>Unidad</option>
                        <option value="caja" <?= $producto['unidad_medida'] == 'caja' ? 'selected' : '' ?>>Caja</option>
                        <option value="kg" <?= $producto['unidad_medida'] == 'kg' ? 'selected' : '' ?>>Kilogramo</option>
                        <option value="metro" <?= $producto['unidad_medida'] == 'metro' ? 'selected' : '' ?>>Metro</option>
                        <option value="litro" <?= $producto['unidad_medida'] == 'litro' ? 'selected' : '' ?>>Litro</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar Producto
            </button>
            <a href="<?= APP_URL ?>/producto" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<script>
// Calcular margen inicial
calcularMargen();

document.getElementById('precio_compra').addEventListener('input', calcularMargen);
document.getElementById('precio_venta').addEventListener('input', calcularMargen);

function calcularMargen() {
    const precioCompra = parseFloat(document.getElementById('precio_compra').value) || 0;
    const precioVenta = parseFloat(document.getElementById('precio_venta').value) || 0;

    if (precioCompra > 0) {
        const margen = ((precioVenta - precioCompra) / precioCompra * 100).toFixed(2);
        const utilidad = precioVenta - precioCompra;

        document.getElementById('margen').innerHTML =
            `<strong>Margen: ${margen}%</strong> | Utilidad: <?= MONEDA_SIMBOLO ?> ${utilidad.toFixed(2)}`;

        if (margen < 0) {
            document.getElementById('margen').style.color = 'red';
        } else {
            document.getElementById('margen').style.color = 'green';
        }
    }
}
</script>
