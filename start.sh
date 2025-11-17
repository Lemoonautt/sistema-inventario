#!/bin/bash
# ============================================================================
# Script: start.sh
# Descripción: Inicia los contenedores (si ya existen, los levanta)
# Uso: ./start.sh
# ============================================================================

echo ""
echo "========================================================================"
echo "  INICIO - Sistema de Inventario"
echo "========================================================================"
echo ""

# Verificar si Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "ERROR: Docker no está instalado."
    echo "Instala Docker desde: https://docs.docker.com/engine/install/"
    exit 1
fi

echo "Iniciando contenedores..."
echo ""

# Levantar contenedores (sin rebuild)
docker-compose up -d

if [ $? -ne 0 ]; then
    echo ""
    echo "ERROR: No se pudieron iniciar los contenedores."
    echo "Revisa los logs con: docker-compose logs"
    exit 1
fi

echo ""
echo "========================================================================"
echo "  CONTENEDORES INICIADOS EXITOSAMENTE!"
echo "========================================================================"
echo ""
echo "Sistema disponible en:"
echo "  - Aplicación Web: http://localhost:8080/public"
echo "  - phpMyAdmin:     http://localhost:8081"
echo ""
echo "Estado de los contenedores:"
docker-compose ps
echo ""
echo "NOTA: Si es la primera vez, usa './setup.sh' para crear la BD"
echo ""
