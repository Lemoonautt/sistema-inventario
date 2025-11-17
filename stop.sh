#!/bin/bash
# ============================================================================
# Script: stop.sh
# Descripción: Detiene los contenedores (mantiene los datos)
# Uso: ./stop.sh
# ============================================================================

echo ""
echo "========================================================================"
echo "  DETENCIÓN - Sistema de Inventario"
echo "========================================================================"
echo ""

echo "Deteniendo contenedores..."
docker-compose stop

if [ $? -ne 0 ]; then
    echo "ERROR: No se pudieron detener los contenedores"
    exit 1
fi

echo ""
echo "========================================================================"
echo "  CONTENEDORES DETENIDOS EXITOSAMENTE"
echo "========================================================================"
echo ""
echo "Los datos se mantienen intactos."
echo ""
echo "Para volver a iniciar usa: ./start.sh"
echo ""
