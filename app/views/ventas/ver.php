<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-receipt"></i>
        Detalle de Venta #<?= $venta['id'] ?>
    </h1>
    <div class="page-actions">
        <a href="<?= APP_URL ?>/venta" class="btn btn-light">
            <i class="fas fa-arrow-left"></i> Volver a Ventas
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </div>
</div>

<div class="invoice-container">
    <!-- Información de la venta -->
    <div class="invoice-header">
        <div class="invoice-info">
            <h2>VENTA #<?= str_pad($venta['id'], 6, '0', STR_PAD_LEFT) ?></h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Fecha:</label>
                    <span><?= date('d/m/Y H:i', strtotime($venta['fecha_venta'])) ?></span>
                </div>
                <div class="info-item">
                    <label>Estado:</label>
                    <?php if ($venta['estado'] == 'completada'): ?>
                        <span class="badge badge-success">Completada</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Anulada</span>
                    <?php endif; ?>
                </div>
                <div class="info-item">
                    <label>Método de Pago:</label>
                    <span><?= ucfirst($venta['tipo_pago']) ?></span>
                </div>
            </div>
        </div>

        <div class="client-info">
            <h3>Cliente</h3>
            <p><strong><?= htmlspecialchars($venta['cliente_nombre']) ?></strong></p>
            <?php if (!empty($venta['cliente_nit']) && $venta['cliente_nit'] != '0'): ?>
                <p>NIT: <?= $venta['cliente_nit'] ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Productos -->
    <div class="invoice-products">
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-right">Precio Unit.</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($venta['detalles'] as $detalle): ?>
                <tr>
                    <td><?= htmlspecialchars($detalle['producto_codigo']) ?></td>
                    <td><?= htmlspecialchars($detalle['producto_nombre']) ?></td>
                    <td class="text-center"><?= $detalle['cantidad'] ?></td>
                    <td class="text-right"><?= MONEDA_SIMBOLO ?> <?= number_format($detalle['precio_unitario'], 2) ?></td>
                    <td class="text-right"><?= MONEDA_SIMBOLO ?> <?= number_format($detalle['subtotal'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Totales -->
    <div class="invoice-totals">
        <div class="totals-box">
            <div class="total-row">
                <span>Subtotal:</span>
                <span><?= MONEDA_SIMBOLO ?> <?= number_format($venta['subtotal'], 2) ?></span>
            </div>
            <?php if ($venta['descuento'] > 0): ?>
            <div class="total-row">
                <span>Descuento:</span>
                <span>- <?= MONEDA_SIMBOLO ?> <?= number_format($venta['descuento'], 2) ?></span>
            </div>
            <?php endif; ?>
            <div class="total-row total-final">
                <span>TOTAL:</span>
                <span><?= MONEDA_SIMBOLO ?> <?= number_format($venta['total'], 2) ?></span>
            </div>
        </div>
    </div>

    <?php if (!empty($venta['observaciones'])): ?>
    <div class="invoice-notes">
        <h4>Observaciones:</h4>
        <p><?= nl2br(htmlspecialchars($venta['observaciones'])) ?></p>
    </div>
    <?php endif; ?>

    <?php if ($venta['estado'] == 'completada'): ?>
    <div class="invoice-actions no-print">
        <a href="<?= APP_URL ?>/venta/anular?id=<?= $venta['id'] ?>"
           class="btn btn-danger"
           onclick="return confirm('¿Está seguro de anular esta venta?')">
            <i class="fas fa-ban"></i> Anular Venta
        </a>
    </div>
    <?php endif; ?>
</div>

<style>
.invoice-container {
    max-width: 900px;
    margin: 0 auto;
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 1px 3px var(--shadow);
    border: 1px solid var(--border);
}

.invoice-header {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 2px solid var(--border);
}

.invoice-info h2 {
    color: var(--primary);
    margin-bottom: 20px;
    font-size: 24px;
}

.info-grid {
    display: grid;
    gap: 12px;
}

.info-item {
    display: flex;
    gap: 10px;
}

.info-item label {
    font-weight: 600;
    color: var(--text);
    min-width: 120px;
}

.info-item span {
    color: var(--text);
}

.client-info {
    background: var(--light);
    padding: 20px;
    border-radius: 8px;
}

.client-info h3 {
    color: var(--text);
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
    font-weight: 600;
}

.client-info p {
    margin: 5px 0;
    color: var(--text);
}

.invoice-products {
    margin-bottom: 30px;
}

.invoice-table {
    width: 100%;
    border-collapse: collapse;
}

.invoice-table thead {
    background: var(--light-bg);
}

.invoice-table th {
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: var(--text);
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.invoice-table td {
    padding: 14px 12px;
    border-bottom: 1px solid var(--border-light);
    color: var(--text);
}

.invoice-table tbody tr:last-child td {
    border-bottom: none;
}

.invoice-table tbody tr:hover {
    background: var(--light);
}

.invoice-totals {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 30px;
}

.totals-box {
    min-width: 350px;
    background: var(--light-bg);
    padding: 20px;
    border-radius: 8px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    font-size: 15px;
    color: var(--text);
}

.total-row span:last-child {
    font-weight: 600;
}

.total-final {
    margin-top: 12px;
    padding-top: 16px;
    border-top: 2px solid var(--border);
    font-size: 18px;
    font-weight: 700;
}

.total-final span {
    font-weight: 700;
}

.total-final span:last-child {
    color: var(--primary);
    font-size: 24px;
}

.invoice-notes {
    background: var(--light);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.invoice-notes h4 {
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 10px;
    color: var(--text);
}

.invoice-notes p {
    color: var(--text);
    line-height: 1.6;
}

.invoice-actions {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid var(--border);
}

/* Estilos de impresión */
@media print {
    body {
        background: white;
    }

    .navbar,
    .page-header,
    .no-print,
    .invoice-actions {
        display: none !important;
    }

    .invoice-container {
        box-shadow: none;
        border: none;
        padding: 20px;
    }

    .main-container {
        padding: 0;
    }

    .invoice-header {
        page-break-after: avoid;
    }

    .invoice-table {
        page-break-inside: avoid;
    }
}

@media (max-width: 768px) {
    .invoice-container {
        padding: 20px;
    }

    .invoice-header {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .totals-box {
        min-width: 100%;
    }
}
</style>
