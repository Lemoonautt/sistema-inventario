#!/bin/bash
# ============================================================================
# Script: restart.sh
# Descripción: Reinicia los contenedores SIN perder datos
# Uso: ./restart.sh
# ============================================================================

echo ""
echo "========================================================================"
echo "  REINICIO RÁPIDO - Sistema de Inventario"
echo "========================================================================"
echo ""
echo "Este script va a:"
echo "  1. Detener todos los contenedores"
echo "  2. Iniciarlos nuevamente"
echo ""
echo "NOTA: Los datos se mantienen intactos"
echo ""

echo "[1/2] Deteniendo contenedores..."
docker-compose down
if [ $? -ne 0 ]; then
    echo "ERROR: No se pudieron detener los contenedores"
    exit 1
fi

echo ""
echo "[2/2] Iniciando contenedores..."
docker-compose up -d
if [ $? -ne 0 ]; then
    echo "ERROR: No se pudieron iniciar los contenedores"
    exit 1
fi

echo ""
echo "========================================================================"
echo "  REINICIO COMPLETADO!"
echo "========================================================================"
echo ""
echo "Sistema disponible en:"
echo "  - Aplicación Web: http://localhost:8080/public"
echo "  - phpMyAdmin:     http://localhost:8081"
echo ""
echo "Estado de los contenedores:"
docker-compose ps
echo ""
