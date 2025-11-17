<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-truck-loading"></i>
        Nueva Compra
    </h1>
    <div class="page-actions">
        <a href="<?= APP_URL ?>/compra" class="btn btn-light">
            <i class="fas fa-list"></i> Ver Compras
        </a>
    </div>
</div>

<div class="pos-container">
    <form method="POST" action="<?= APP_URL ?>/compra/guardar" id="formCompra">

        <!-- Barra de búsqueda -->
        <div class="pos-search-bar">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text"
                       id="buscarProducto"
                       class="search-input"
                       placeholder="Buscar producto por código o nombre..."
                       autocomplete="off"
                       autofocus
                       oninput="buscarProductos(this.value)">
            </div>
            <div id="resultadosBusqueda" class="search-results"></div>
        </div>

        <div class="pos-layout">
            <!-- Lista de productos -->
            <div class="pos-products">
                <div class="pos-header">
                    <h3>Productos de la Compra</h3>
                    <span id="contadorProductos" class="counter">0 productos</span>
                </div>

                <div class="products-header">
                    <div>Producto</div>
                    <div>Cantidad</div>
                    <div>Precio Compra</div>
                    <div>Subtotal</div>
                    <div></div>
                </div>

                <div id="detallesCompra" class="products-list">
                    <div class="empty-state">
                        <i class="fas fa-boxes"></i>
                        <p>No hay productos agregados</p>
                        <small>Utiliza el buscador para agregar productos a la compra</small>
                    </div>
                </div>
            </div>

            <!-- Panel lateral -->
            <div class="pos-sidebar">
                <!-- Proveedor -->
                <div class="sidebar-section">
                    <label class="section-label">Proveedor <span class="required">*</span></label>
                    <select id="proveedor_id" name="proveedor_id" class="form-control" required>
                        <option value="">Seleccione un proveedor</option>
                        <?php foreach ($proveedores as $proveedor): ?>
                        <option value="<?= $proveedor['id'] ?>">
                            <?= htmlspecialchars($proveedor['nombre']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fecha -->
                <div class="sidebar-section">
                    <label class="section-label">Fecha de Compra</label>
                    <input type="datetime-local"
                           id="fecha_compra"
                           name="fecha_compra"
                           class="form-control"
                           value="<?= date('Y-m-d\TH:i') ?>">
                </div>

                <!-- Totales -->
                <div class="sidebar-section totals-section">
                    <div class="total-line total-final">
                        <span>Total</span>
                        <span id="displayTotal"><?= MONEDA_SIMBOLO ?> 0.00</span>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="sidebar-section">
                    <label class="section-label">Observaciones (opcional)</label>
                    <textarea id="observaciones"
                              name="observaciones"
                              class="form-control"
                              rows="2"
                              placeholder="Notas adicionales..."></textarea>
                </div>

                <!-- Botones -->
                <div class="sidebar-actions">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <i class="fas fa-save"></i> Registrar Compra
                    </button>
                    <a href="<?= APP_URL ?>/compra" class="btn btn-light btn-block">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>

        <input type="hidden" name="total" id="total" value="0">
    </form>
</div>

<style>
.pos-container {
    max-width: 1400px;
    margin: 0 auto;
}

/* Barra de búsqueda */
.pos-search-bar {
    background: white;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px var(--shadow);
    border: 1px solid var(--border);
    position: relative;
}

.search-input-wrapper {
    position: relative;
}

.search-input-wrapper i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    font-size: 18px;
}

.search-input {
    width: 100%;
    padding: 14px 16px 14px 48px;
    font-size: 16px;
    border: 2px solid var(--border);
    border-radius: 8px;
    transition: all 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
}

.search-results {
    display: none;
    background: white;
    border: 1px solid var(--border);
    border-radius: 8px;
    margin-top: 12px;
    max-height: 320px;
    overflow-y: auto;
    box-shadow: 0 4px 12px var(--shadow-md);
}

.resultado-item {
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.15s;
}

.resultado-item:hover {
    background: var(--light);
}

.resultado-item:last-child {
    border-bottom: none;
}

.resultado-item .precio {
    font-weight: 600;
    color: var(--success);
    font-size: 15px;
}

/* Layout */
.pos-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 24px;
    align-items: start;
}

/* Panel de productos */
.pos-products {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
}

.pos-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--light);
}

.pos-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--dark-text);
}

.counter {
    background: var(--success);
    color: white;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 500;
}

.products-header {
    display: grid;
    grid-template-columns: 2fr 120px 120px 120px 50px;
    gap: 16px;
    padding: 12px 24px;
    background: var(--light-bg);
    font-size: 12px;
    font-weight: 600;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.products-list {
    min-height: 280px;
    max-height: 480px;
    overflow-y: auto;
}

.empty-state {
    text-align: center;
    padding: 64px 24px;
    color: var(--text-light);
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.3;
    color: var(--success);
}

.empty-state p {
    font-size: 16px;
    margin: 8px 0 4px;
    color: var(--text);
}

.empty-state small {
    font-size: 13px;
}

.product-row {
    display: grid;
    grid-template-columns: 2fr 120px 120px 120px 50px;
    gap: 16px;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border-light);
    align-items: center;
}

.product-row:hover {
    background: var(--light);
}

.product-info {
    min-width: 0;
}

.product-info strong {
    display: block;
    color: var(--dark-text);
    font-size: 14px;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.product-info small {
    color: var(--text-light);
    font-size: 12px;
}

.product-qty input {
    width: 100%;
    padding: 6px 10px;
    border: 1px solid var(--border);
    border-radius: 6px;
    text-align: center;
    font-size: 14px;
    font-weight: 500;
}

.product-qty input:focus {
    outline: none;
    border-color: var(--primary);
}

.product-price input {
    width: 100%;
    padding: 6px 10px;
    border: 1px solid var(--border);
    border-radius: 6px;
    text-align: right;
    font-size: 14px;
    font-weight: 500;
}

.product-price input:focus {
    outline: none;
    border-color: var(--primary);
}

.product-subtotal {
    font-weight: 600;
    color: var(--success);
    text-align: right;
    font-size: 15px;
}

.btn-remove {
    background: transparent;
    color: var(--text-light);
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-remove:hover {
    background: #fef2f2;
    color: var(--danger);
}

/* Sidebar */
.pos-sidebar {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px var(--shadow);
    border: 1px solid var(--border);
    position: sticky;
    top: 24px;
}

.sidebar-section {
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border-light);
}

.sidebar-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.section-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.required {
    color: var(--danger);
}

/* Totales */
.totals-section {
    background: var(--light-bg);
    margin: 0 -24px;
    padding: 20px 24px !important;
    border: none !important;
}

.total-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    font-size: 14px;
    color: var(--text);
}

.total-final {
    margin-top: 12px;
    padding-top: 16px !important;
    border-top: 2px solid var(--border) !important;
}

.total-final span {
    font-size: 16px;
    font-weight: 700;
}

.total-final span:last-child {
    font-size: 24px;
    color: var(--success);
}

/* Botones */
.sidebar-actions {
    margin-top: 24px;
}

.sidebar-actions .btn {
    margin-bottom: 8px;
}

.btn-block {
    width: 100%;
    display: block;
    text-align: center;
}

@media (max-width: 1024px) {
    .pos-layout {
        grid-template-columns: 1fr;
    }

    .pos-sidebar {
        position: static;
    }

    .products-header,
    .product-row {
        grid-template-columns: 1.5fr 100px 100px 100px 40px;
        gap: 12px;
    }
}
</style>

<script>
let lineaIndex = 0;
let searchTimeout;
let productosEnCompra = [];

function buscarProductos(termino) {
    clearTimeout(searchTimeout);

    const resultados = document.getElementById('resultadosBusqueda');

    if (termino.length < 2) {
        resultados.style.display = 'none';
        return;
    }

    searchTimeout = setTimeout(() => {
        fetch(`<?= APP_URL ?>/producto/buscar?q=${encodeURIComponent(termino)}`)
            .then(r => r.json())
            .then(productos => {
                if (productos.length === 0) {
                    resultados.innerHTML = '<div class="resultado-item" style="cursor: default;"><span>No se encontraron productos</span></div>';
                } else {
                    resultados.innerHTML = productos.map(p => {
                        const espacioDisponible = p.stock_maximo - p.stock_actual;
                        const stockWarning = espacioDisponible <= 0 ?
                            '<br><small style="color: #dc2626; font-weight: 600;">⚠️ Stock en máximo</small>' :
                            `<br><small style="color: #059669;">Espacio disponible: ${espacioDisponible} unidades (máx: ${p.stock_maximo})</small>`;

                        return `
                        <div class="resultado-item"
                             data-id="${p.id}"
                             data-codigo="${escapeHtml(p.codigo)}"
                             data-nombre="${escapeHtml(p.nombre)}"
                             data-precio="${p.precio_compra}"
                             data-stock-actual="${p.stock_actual}"
                             data-stock-maximo="${p.stock_maximo}"
                             onclick="agregarProductoDesdeElemento(this)">
                            <div>
                                <strong>${escapeHtml(p.codigo)}</strong> - ${escapeHtml(p.nombre)}
                                <br><small style="color: var(--text-light);">Stock actual: ${p.stock_actual}</small>
                                ${stockWarning}
                            </div>
                            <span class="precio"><?= MONEDA_SIMBOLO ?> ${parseFloat(p.precio_compra).toFixed(2)}</span>
                        </div>
                        `;
                    }).join('');
                }
                resultados.style.display = 'block';
            })
            .catch(err => {
                console.error('Error:', err);
                resultados.style.display = 'none';
            });
    }, 300);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function agregarProductoDesdeElemento(elemento) {
    const id = parseInt(elemento.dataset.id);
    const codigo = elemento.dataset.codigo;
    const nombre = elemento.dataset.nombre;
    const precio = parseFloat(elemento.dataset.precio);
    const stockActual = parseInt(elemento.dataset.stockActual);
    const stockMaximo = parseInt(elemento.dataset.stockMaximo);

    agregarProducto(id, codigo, nombre, precio, stockActual, stockMaximo);
}

function agregarProducto(id, codigo, nombre, precio, stockActual, stockMaximo) {
    const productoExistente = productosEnCompra.find(p => p.id === id);

    if (productoExistente) {
        productoExistente.cantidad++;
        actualizarVistaProducto(productoExistente);
    } else {
        const nuevoProducto = {
            id: id,
            codigo: codigo,
            nombre: nombre,
            precio: precio,
            stockActual: stockActual,
            stockMaximo: stockMaximo,
            cantidad: 1,
            index: lineaIndex++
        };

        productosEnCompra.push(nuevoProducto);
        agregarProductoAVista(nuevoProducto);
    }

    document.getElementById('buscarProducto').value = '';
    document.getElementById('resultadosBusqueda').style.display = 'none';
    document.getElementById('buscarProducto').focus();

    calcularTotales();
    actualizarContador();
}

function agregarProductoAVista(producto) {
    const detallesCompra = document.getElementById('detallesCompra');

    const emptyState = detallesCompra.querySelector('.empty-state');
    if (emptyState) {
        emptyState.remove();
    }

    const nombreEscapado = escapeHtml(producto.nombre);
    const codigoEscapado = escapeHtml(producto.codigo);

    const espacioDisponible = producto.stockMaximo - producto.stockActual;
    const stockInfo = espacioDisponible <= 0 ?
        '<small style="color: #dc2626;">⚠️ Stock en máximo</small>' :
        `<small style="color: #059669;">Espacio: ${espacioDisponible} unidades</small>`;

    const html = `
        <div class="product-row" id="producto${producto.index}">
            <input type="hidden" name="producto_id[]" value="${producto.id}">
            <div class="product-info">
                <strong>${nombreEscapado}</strong>
                <small>Código: ${codigoEscapado} | Stock: ${producto.stockActual}/${producto.stockMaximo}</small>
                ${stockInfo}
            </div>
            <div class="product-qty">
                <input type="number"
                       name="cantidad[]"
                       id="cantidad${producto.index}"
                       value="${producto.cantidad}"
                       min="1"
                       max="${espacioDisponible > 0 ? espacioDisponible : 1}"
                       step="1"
                       onchange="actualizarCantidad(${producto.index}, this.value)">
            </div>
            <div class="product-price">
                <input type="number"
                       name="precio_unitario[]"
                       value="${producto.precio.toFixed(2)}"
                       min="0"
                       step="0.01"
                       onchange="actualizarPrecio(${producto.index}, this.value)">
            </div>
            <div class="product-subtotal" id="subtotal${producto.index}">
                <?= MONEDA_SIMBOLO ?> ${(producto.precio * producto.cantidad).toFixed(2)}
            </div>
            <div>
                <button type="button" class="btn-remove" onclick="eliminarProducto(${producto.index})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    detallesCompra.insertAdjacentHTML('beforeend', html);
}

function actualizarVistaProducto(producto) {
    const productoDiv = document.getElementById(`producto${producto.index}`);
    if (productoDiv) {
        const cantidadInput = productoDiv.querySelector('input[name="cantidad[]"]');
        cantidadInput.value = producto.cantidad;

        const subtotalDiv = document.getElementById(`subtotal${producto.index}`);
        subtotalDiv.innerHTML = `<?= MONEDA_SIMBOLO ?> ${(producto.precio * producto.cantidad).toFixed(2)}`;
    }
}

function actualizarCantidad(index, nuevaCantidad) {
    const producto = productosEnCompra.find(p => p.index === index);
    if (producto) {
        producto.cantidad = parseInt(nuevaCantidad);
        actualizarVistaProducto(producto);
        calcularTotales();
    }
}

function actualizarPrecio(index, nuevoPrecio) {
    const producto = productosEnCompra.find(p => p.index === index);
    if (producto) {
        producto.precio = parseFloat(nuevoPrecio);
        actualizarVistaProducto(producto);
        calcularTotales();
    }
}

function eliminarProducto(index) {
    productosEnCompra = productosEnCompra.filter(p => p.index !== index);

    const productoDiv = document.getElementById(`producto${index}`);
    if (productoDiv) {
        productoDiv.remove();
    }

    const detallesCompra = document.getElementById('detallesCompra');
    if (productosEnCompra.length === 0) {
        detallesCompra.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-boxes"></i>
                <p>No hay productos agregados</p>
                <small>Utiliza el buscador para agregar productos a la compra</small>
            </div>
        `;
    }

    calcularTotales();
    actualizarContador();
}

function calcularTotales() {
    let total = 0;

    productosEnCompra.forEach(producto => {
        total += producto.precio * producto.cantidad;
    });

    document.getElementById('total').value = total.toFixed(2);
    document.getElementById('displayTotal').textContent = '<?= MONEDA_SIMBOLO ?> ' + total.toFixed(2);
}

function actualizarContador() {
    const contador = document.getElementById('contadorProductos');
    const cantidadTotal = productosEnCompra.reduce((sum, p) => sum + p.cantidad, 0);
    contador.textContent = `${productosEnCompra.length} productos (${cantidadTotal} unidades)`;
}

// Event listeners
document.getElementById('buscarProducto').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const resultados = document.querySelectorAll('.resultado-item');
        if (resultados.length > 0 && !resultados[0].textContent.includes('No se encontraron')) {
            resultados[0].click();
        }
    }
});

document.addEventListener('click', function(e) {
    const busqueda = document.querySelector('.pos-search-bar');
    if (busqueda && !busqueda.contains(e.target)) {
        document.getElementById('resultadosBusqueda').style.display = 'none';
    }
});

document.getElementById('formCompra').addEventListener('submit', function(e) {
    if (productosEnCompra.length === 0) {
        e.preventDefault();
        alert('Debe agregar al menos un producto a la compra');
        return false;
    }

    const proveedorId = document.getElementById('proveedor_id').value;
    if (!proveedorId) {
        e.preventDefault();
        alert('Debe seleccionar un proveedor');
        return false;
    }
});

actualizarContador();
</script>
