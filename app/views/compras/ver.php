<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-file-invoice"></i>
        Detalle de Compra #<?= $compra['numero_compra'] ?>
    </h1>
    <div class="page-actions">
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="fas fa-print"></i> Imprimir
        </button>
        <a href="<?= APP_URL ?>/compra" class="btn btn-light">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="invoice-container">
    <!-- Información de la compra -->
    <div class="invoice-card">
        <div class="invoice-header">
            <div class="invoice-info">
                <h2>Información de la Compra</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="label">Número de Compra:</span>
                        <span class="value"><?= htmlspecialchars($compra['numero_compra']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Fecha:</span>
                        <span class="value"><?= date('d/m/Y H:i', strtotime($compra['fecha_compra'])) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Proveedor:</span>
                        <span class="value"><?= htmlspecialchars($compra['proveedor_nombre']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Usuario:</span>
                        <span class="value"><?= htmlspecialchars($compra['usuario'] ?? 'admin') ?></span>
                    </div>
                </div>

                <?php if (!empty($compra['observaciones'])): ?>
                <div class="info-item observaciones">
                    <span class="label">Observaciones:</span>
                    <span class="value"><?= htmlspecialchars($compra['observaciones']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Detalle de productos -->
        <div class="invoice-details">
            <h3>Productos</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th style="text-align: center;">Cantidad</th>
                            <th style="text-align: right;">Precio Unit.</th>
                            <th style="text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($compra['detalles'] as $detalle): ?>
                        <tr>
                            <td><?= htmlspecialchars($detalle['producto_codigo']) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($detalle['producto_nombre']) ?></strong>
                            </td>
                            <td style="text-align: center;"><?= $detalle['cantidad'] ?></td>
                            <td style="text-align: right;">
                                <?= MONEDA_SIMBOLO ?> <?= number_format($detalle['precio_unitario'], 2) ?>
                            </td>
                            <td style="text-align: right;">
                                <strong><?= MONEDA_SIMBOLO ?> <?= number_format($detalle['subtotal'], 2) ?></strong>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="4" style="text-align: right;"><strong>TOTAL:</strong></td>
                            <td style="text-align: right;">
                                <strong class="total-amount">
                                    <?= MONEDA_SIMBOLO ?> <?= number_format($compra['total'], 2) ?>
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.invoice-container {
    max-width: 1000px;
    margin: 0 auto;
}

.invoice-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
}

.invoice-header {
    background: var(--light-bg);
    padding: 32px;
    border-bottom: 2px solid var(--border);
}

.invoice-info h2 {
    margin: 0 0 24px 0;
    color: var(--dark-text);
    font-size: 20px;
    font-weight: 600;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-item.observaciones {
    grid-column: 1 / -1;
    margin-top: 8px;
}

.info-item .label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item .value {
    font-size: 15px;
    color: var(--dark-text);
    font-weight: 500;
}

.invoice-details {
    padding: 32px;
}

.invoice-details h3 {
    margin: 0 0 20px 0;
    color: var(--dark-text);
    font-size: 18px;
    font-weight: 600;
}

.table-responsive {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead {
    background: var(--light-bg);
}

.table th {
    padding: 12px 16px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid var(--border);
}

.table td {
    padding: 16px;
    border-bottom: 1px solid var(--border-light);
    color: var(--text);
    font-size: 14px;
}

.table tbody tr:hover {
    background: var(--light);
}

.table tbody tr:last-child td {
    border-bottom: none;
}

.table tfoot {
    border-top: 2px solid var(--border);
}

.total-row td {
    padding: 20px 16px !important;
    font-size: 16px;
    border-bottom: none !important;
}

.total-amount {
    color: var(--success);
    font-size: 20px;
}

/* Print styles */
@media print {
    .page-header .page-actions,
    .sidebar {
        display: none !important;
    }

    .invoice-container {
        max-width: 100%;
    }

    .invoice-card {
        box-shadow: none;
        border: 1px solid #ddd;
    }
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }

    .invoice-header,
    .invoice-details {
        padding: 20px;
    }

    .table {
        font-size: 12px;
    }

    .table th,
    .table td {
        padding: 8px;
    }
}
</style>
