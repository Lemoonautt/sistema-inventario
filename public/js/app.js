/**
 * JAVASCRIPT DEL SISTEMA DE INVENTARIO
 * Funciones generales del sistema
 */

// Confirmar eliminación
document.querySelectorAll('[data-confirm]').forEach(element => {
    element.addEventListener('click', function(e) {
        const mensaje = this.getAttribute('data-confirm') || '¿Está seguro?';
        if (!confirm(mensaje)) {
            e.preventDefault();
        }
    });
});

// Auto-cerrar alertas después de 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});

// Formatear números como moneda
function formatearMoneda(numero) {
    return new Intl.NumberFormat('es-BO', {
        style: 'currency',
        currency: 'BOB'
    }).format(numero);
}

// Busqueda en tiempo real
function busquedaEnTiempoReal(inputId, url, callbackFn) {
    const input = document.getElementById(inputId);
    if (!input) return;

    let timeout = null;
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value;

        if (query.length < 2) return;

        timeout = setTimeout(() => {
            fetch(url + '?q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => callbackFn(data))
                .catch(error => console.error('Error:', error));
        }, 300);
    });
}

// Validar formularios
function validarFormulario(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    form.addEventListener('submit', function(e) {
        const requiredInputs = form.querySelectorAll('[required]');
        let valid = true;

        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                valid = false;
                input.style.borderColor = '#ef4444';
            } else {
                input.style.borderColor = '#e5e7eb';
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('Por favor complete todos los campos requeridos');
        }
    });
}

// Imprimir reporte
function imprimirReporte() {
    window.print();
}

console.log('Sistema de Inventario cargado correctamente');
