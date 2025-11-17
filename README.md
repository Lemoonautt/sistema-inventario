# 📦 Sistema de Gestión de Inventario
## Proyecto del Grupo 2

Sistema de gestión de inventario desarrollado con PHP y MySQL, que permite controlar productos, ventas, compras y generar reportes.

---

## 👥 Integrantes del Grupo 2

- Limberg Edgar Montes Tancara
- Jorge Luis Quispe Mollericona
- Elionai Paredes Rojas
---

## 📋 Descripción del Proyecto

Sistema desarrollado para la materia de Teoría General de Sistemas con el objetivo de crear una solución completa para la gestión de inventarios en pequeñas y medianas empresas.

### 🎯 Objetivos del Proyecto

1. Implementar un sistema MVC en PHP puro
2. Diseñar una base de datos normalizada con triggers
3. Crear una interfaz moderna y responsive
4. Automatizar procesos de stock con alertas
5. Generar reportes útiles para la toma de decisiones

---

## ✨ Características Implementadas

### Módulos Principales

| Módulo | Funcionalidad | Estado |
|--------|---------------|--------|
| 🏠 Dashboard | Panel con estadísticas en tiempo real | ✅ Completado |
| 📦 Productos | CRUD de productos con categorías | ✅ Completado |
| 🏷️ Categorías | Gestión de categorías de productos | ✅ Completado |
| 🛒 Punto de Venta | Sistema POS moderno con búsqueda en tiempo real | ✅ Completado |
| 📥 Compras | Registro de compras a proveedores | ✅ Completado |
| 👥 Clientes | Gestión de clientes | ✅ Completado |
| 🚚 Proveedores | Gestión de proveedores | ✅ Completado |
| 👤 Usuarios | Sistema de autenticación y roles | ✅ Completado |
| 📊 Reportes | Stock, ventas y utilidades | ✅ Completado |



## 🛠️ Tecnologías Utilizadas

### Backend
- **PHP 8.2**: Lenguaje principal
- **MySQL 8.0**: Base de datos
- **PDO**: Capa de abstracción de base de datos

### Frontend
- **HTML5**: Estructura
- **CSS3**: Estilos con variables CSS
- **JavaScript Vanilla**: Interactividad
- **Font Awesome**: Iconos

### DevOps
- **Docker**: Contenedorización
- **Docker Compose**: Orquestación de servicios
- **Git**: Control de versiones

---

## 🚀 Cómo Levantar el Proyecto

### Opción 1: Con Docker (Recomendado - Más Fácil)

#### Requisitos
- Docker Desktop instalado
- Puertos libres: 8080, 8081, 3307

#### Pasos

1. **Clonar/descargar el proyecto**
   ```bash
   cd /ruta/al/proyecto
   ```

2. **Ejecutar el script de setup** (primera vez)
   ```bash
   # Linux/Mac
   chmod +x setup.sh
   ./setup.sh

   # Windows
   .\setup.bat
   ```

3. **Acceder al sistema**
   - Sistema: http://localhost:8080/public
   - phpMyAdmin: http://localhost:8081

4. **Credenciales de acceso**
   - Usuario: `admin`
   - Contraseña: `admin123`

#### Comandos útiles

```bash
# Iniciar el sistema
./start.sh

# Detener el sistema
./stop.sh

# Reiniciar
./restart.sh

# Resetear base de datos (borra todo y recrea)
./reset-db.sh

# Ver logs
docker-compose logs -f
```

---

### Opción 2: Sin Docker (Instalación Manual)

#### Requisitos
- PHP 8.2+
- MySQL 8.0+
- Apache o Nginx
- Extensiones: pdo, pdo_mysql, mbstring

#### Pasos

1. **Crear la base de datos**
   ```bash
   mysql -u root -p
   ```
   ```sql
   CREATE DATABASE inventario_sistema;
   CREATE USER 'inventario_user'@'localhost' IDENTIFIED BY 'inventario_pass';
   GRANT ALL PRIVILEGES ON inventario_sistema.* TO 'inventario_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

2. **Importar estructura y datos**
   ```bash
   mysql -u inventario_user -p inventario_sistema < database/schema.sql
   mysql -u inventario_user -p inventario_sistema < database/datos_usuarios.sql
   mysql -u inventario_user -p inventario_sistema < database/seeds.sql
   ```

3. **Configurar Apache/Nginx**
   - DocumentRoot debe apuntar a `/ruta/al/proyecto/public`
   - Habilitar `mod_rewrite` (Apache)

4. **Acceder**
   - http://localhost/public

---

## 📁 Estructura del Proyecto

```
lab1/
├── app/
│   ├── controllers/     # Lógica de negocio (MVC)
│   ├── models/          # Acceso a datos (MVC)
│   └── views/           # Presentación (MVC)
├── config/              # Configuración
├── database/            # SQL: schema, seeds, usuarios
├── public/              # Punto de entrada web
│   ├── css/
│   ├── js/
│   └── index.php
├── docker-compose.yml   # Configuración Docker
└── README.md
```

---

## 💾 Base de Datos

### Diagrama ER

El sistema cuenta con las siguientes entidades principales:

- **productos**: Catálogo de productos
- **categorias**: Clasificación de productos
- **ventas** → **detalle_ventas**: Transacciones de venta
- **compras** → **detalle_compras**: Transacciones de compra
- **clientes**: Registro de clientes
- **proveedores**: Registro de proveedores
- **usuarios**: Sistema de autenticación
- **alertas_stock**: Alertas automáticas

### Triggers Implementados

| Trigger | Evento | Función |
|---------|--------|---------|
| `after_detalle_venta_insert` | Después de insertar venta | Reduce stock del producto |
| `after_detalle_compra_insert` | Después de insertar compra | Aumenta stock del producto |
| `after_producto_update` | Después de actualizar producto | Genera alertas de stock bajo |

---

## 👤 Usuarios de Prueba

| Usuario | Contraseña | Rol | Permisos |
|---------|-----------|-----|----------|
| admin | admin123 | Administrador | Acceso total |
| vendedor | admin123 | Vendedor | Ventas y consultas |
| almacen | admin123 | Almacenero | Compras y stock |

---

## 📊 Datos de Ejemplo

El sistema incluye datos de prueba:
- **10 categorías** (Herramientas, Electrónica, Oficina, etc.)
- **8 productos** de ejemplo
- **3 proveedores**
- **4 clientes**
- **1 venta** de ejemplo

---

## 🎨 Capturas de Pantalla

### Dashboard
El panel principal muestra:
- Ventas del mes
- Valor del inventario
- Alertas de stock bajo
- Accesos rápidos

### Punto de Venta (POS)
Interfaz moderna con:
- Búsqueda en tiempo real
- Cálculo automático
- Múltiples métodos de pago

### Gestión de Productos
- Tabla con filtros
- CRUD completo
- Control de stock

---

## 🔧 Funcionalidades Técnicas

### Arquitectura MVC
```
Cliente (navegador)
    ↓
index.php (Front Controller)
    ↓
Controlador (lógica)
    ↓
Modelo (datos)
    ↓
Vista (presentación)
    ↓
Cliente (navegador)
```

### Seguridad Implementada
- ✅ Contraseñas hasheadas (bcrypt)
- ✅ PDO con prepared statements (anti SQL-injection)
- ✅ Escape de HTML (anti XSS)
- ✅ Validación de sesiones
- ✅ Control de acceso por roles

---

## 🐛 Solución de Problemas Comunes

### Error: Puerto 8080 ocupado
```bash
# Editar docker-compose.yml y cambiar:
ports:
  - "8082:80"  # Cambiar 8080 por otro puerto disponible
```

### Error: Contenedores no inician
```bash
# Ver qué está fallando
docker-compose logs

# Limpiar y reiniciar
docker-compose down -v
docker-compose up --build
```

### Error: No se puede conectar a MySQL
```bash
# Esperar a que MySQL termine de iniciar (30-60 segundos)
docker-compose logs mysql

# Verificar estado
docker-compose ps
```

---

## 📚 Documentación Adicional

### Consultas SQL Útiles

El archivo `database/consultas_utiles.sql` contiene queries para:
- Ver productos con stock bajo
- Calcular utilidades
- Obtener estadísticas de ventas
- Consultar alertas activas

### Comandos Docker Útiles

```bash
# Ver logs en tiempo real
docker-compose logs -f

# Acceder a MySQL
docker-compose exec mysql mysql -uinventario_user -pinventario_pass inventario_sistema

# Backup de la base de datos
docker-compose exec mysql mysqldump -uinventario_user -pinventario_pass inventario_sistema > backup.sql

# Restaurar backup
docker-compose exec -T mysql mysql -uinventario_user -pinventario_pass inventario_sistema < backup.sql
```

---

## 🎯 Logros del Proyecto

✅ **Sistema completamente funcional**
- Todos los módulos implementados y probados
- Base de datos normalizada con triggers
- Interfaz moderna y responsive

✅ **Buenas prácticas aplicadas**
- Arquitectura MVC clara
- Código documentado
- Validaciones en backend y frontend
- Seguridad implementada

✅ **Dockerizado y portable**
- Fácil instalación con un comando
- Funciona en cualquier sistema operativo
- Incluye phpMyAdmin para gestión de BD

---

## 📝 Conclusiones

Este proyecto demuestra la implementación completa de un sistema de gestión de inventario usando tecnologías web modernas y buenas prácticas de desarrollo.

### Aprendizajes Clave
- Diseño e implementación de arquitectura MVC
- Manejo de base de datos con triggers y procedimientos
- Desarrollo de interfaces responsive
- Trabajo en equipo con Git
- Dockerización de aplicaciones

### Posibles Mejoras Futuras
- [ ] Implementar gráficos con Chart.js
- [ ] Exportación de reportes a PDF/Excel
- [ ] API REST para integración
- [ ] Notificaciones por email
- [ ] Sistema multi-sucursal

---

## 📞 Información de Contacto

**Grupo 2 - Sistema de Inventario**

Universidad: [Nombre de la Universidad]
Materia: [Nombre de la Materia]
Docente: [Nombre del Docente]
Semestre: [Semestre/Gestión]
Fecha: Noviembre 2024

---

## 🙏 Agradecimientos

Agradecemos a nuestro docente [Nombre] por la guía durante el desarrollo de este proyecto, y a todos los miembros del equipo por su dedicación y esfuerzo.

---

**Desarrollado con ❤️ por el Grupo 2**

*Sistema de Gestión de Inventario - Proyecto Académico 2024*
