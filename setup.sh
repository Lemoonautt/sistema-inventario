#!/bin/bash
# ============================================================================
# Script: setup.sh
# Descripción: Setup completo - Recrea TODO desde cero (contenedores + BD)
# Uso: ./setup.sh
# ============================================================================

echo ""
echo "========================================================================"
echo "  SETUP COMPLETO - Sistema de Inventario"
echo "========================================================================"
echo ""
echo "Este script va a:"
echo "  1. Detener y eliminar todos los contenedores existentes"
echo "  2. Eliminar volúmenes de datos (se perderán todos los datos!)"
echo "  3. Reconstruir las imágenes de Docker"
echo "  4. Crear nuevos contenedores"
echo "  5. Recrear la base de datos desde cero"
echo "  6. Cargar datos iniciales (usuarios, productos, etc.)"
echo ""
echo "ADVERTENCIA: Todos los datos actuales se ELIMINARÁN!"
echo ""
read -p "Presiona ENTER para continuar o Ctrl+C para cancelar..."

echo ""
echo "[1/6] Deteniendo contenedores..."
docker-compose down -v
if [ $? -ne 0 ]; then
    echo "ERROR: No se pudieron detener los contenedores"
    exit 1
fi

echo ""
echo "[2/6] Eliminando imágenes antiguas..."
docker-compose down --rmi local

echo ""
echo "[3/6] Reconstruyendo imágenes de Docker..."
docker-compose build --no-cache
if [ $? -ne 0 ]; then
    echo "ERROR: No se pudieron construir las imágenes"
    exit 1
fi

echo ""
echo "[4/6] Iniciando contenedores nuevos..."
docker-compose up -d
if [ $? -ne 0 ]; then
    echo "ERROR: No se pudieron iniciar los contenedores"
    exit 1
fi

echo ""
echo "[5/6] Esperando que MySQL esté listo..."
sleep 15

echo ""
echo "[6/6] Recreando la base de datos..."
docker-compose exec -T mysql mysql -u root -pinventario2025 -e "DROP DATABASE IF EXISTS inventario_sistema; CREATE DATABASE inventario_sistema;"

echo ""
echo "Cargando schema (estructura de tablas)..."
docker-compose exec -T mysql mysql -u root -pinventario2025 inventario_sistema < database/schema.sql

echo ""
echo "Cargando usuarios iniciales..."
docker-compose exec -T mysql mysql -u root -pinventario2025 inventario_sistema < database/datos_usuarios.sql

echo ""
echo "Cargando datos de prueba (seeders)..."
docker-compose exec -T mysql mysql -u root -pinventario2025 inventario_sistema < database/seeds.sql

echo ""
echo "========================================================================"
echo "  SETUP COMPLETADO EXITOSAMENTE!"
echo "========================================================================"
echo ""
echo "Sistema disponible en:"
echo "  - Aplicación Web: http://localhost:8080/public"
echo "  - phpMyAdmin:     http://localhost:8081"
echo ""
echo "Credenciales de acceso:"
echo "  Usuario:    admin"
echo "  Contraseña: admin123"
echo ""
echo "Estado de los contenedores:"
docker-compose ps
echo ""
