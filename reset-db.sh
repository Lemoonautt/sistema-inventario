#!/bin/bash
# ============================================================================
# Script: reset-db.sh
# Descripción: Resetea SOLO la base de datos
# Uso: ./reset-db.sh
# ============================================================================

echo ""
echo "========================================================================"
echo "  RESET DE BASE DE DATOS - Sistema de Inventario"
echo "========================================================================"
echo ""
echo "Este script va a:"
echo "  1. Eliminar la base de datos actual"
echo "  2. Recrear la estructura de tablas"
echo "  3. Cargar usuarios iniciales"
echo "  4. Cargar datos de prueba"
echo ""
echo "ADVERTENCIA: Todos los datos actuales se ELIMINARÁN!"
echo "Los contenedores seguirán ejecutándose."
echo ""
read -p "Presiona ENTER para continuar o Ctrl+C para cancelar..."

echo ""
echo "[1/4] Eliminando y recreando base de datos..."
docker-compose exec -T mysql mysql -u root -pinventario2025 -e "DROP DATABASE IF EXISTS inventario_sistema; CREATE DATABASE inventario_sistema;"
if [ $? -ne 0 ]; then
    echo "ERROR: No se pudo recrear la base de datos"
    echo "Verifica que los contenedores estén ejecutándose (usa ./start.sh)"
    exit 1
fi

echo ""
echo "[2/4] Cargando schema (estructura de tablas)..."
docker-compose exec -T mysql mysql -u root -pinventario2025 inventario_sistema < database/schema.sql
if [ $? -ne 0 ]; then
    echo "ERROR: No se pudo cargar el schema"
    exit 1
fi

echo ""
echo "[3/4] Cargando usuarios iniciales..."
docker-compose exec -T mysql mysql -u root -pinventario2025 inventario_sistema < database/datos_usuarios.sql
if [ $? -ne 0 ]; then
    echo "ERROR: No se pudieron cargar los usuarios"
    exit 1
fi

echo ""
echo "[4/4] Cargando datos de prueba (seeders)..."
docker-compose exec -T mysql mysql -u root -pinventario2025 inventario_sistema < database/seeds.sql
if [ $? -ne 0 ]; then
    echo "ERROR: No se pudieron cargar los seeders"
    exit 1
fi

echo ""
echo "========================================================================"
echo "  BASE DE DATOS RESETEADA EXITOSAMENTE!"
echo "========================================================================"
echo ""
echo "Credenciales de acceso:"
echo "  Usuario:    admin"
echo "  Contraseña: admin123"
echo ""
echo "Sistema disponible en: http://localhost:8080/public"
echo ""
