# 🎮 Sistema de Ventas Tecnológico - Arquitectura MVC

Sistema completo de gestión de ventas para productos tecnológicos con arquitectura MVC, interfaz cyberpunk moderna y despliegue en Docker.

## 🚀 Características

- ✅ **Arquitectura MVC** - Modelos, Vistas y Controladores completamente separados
- ✅ **Autenticación** - Sistema de login seguro con hash SHA-256
- ✅ **20 Productos Reales** - Catálogo de productos tecnológicos
- ✅ **Gestión de Ventas** - Crear ventas con carrito dinámico
- ✅ **Cálculo Automático** - Subtotal, IGV (18%) y Total calculados correctamente
- ✅ **Historial de Ventas** - Visualizar todas las ventas registradas
- ✅ **Dashboard** - Estadísticas en tiempo real
- ✅ **Interfaz Cyberpunk** - Diseño futurista, responsive y amigable
- ✅ **Prepared Statements** - Consultas seguras contra inyección SQL
- ✅ **Docker** - Despliegue fácil con Docker Compose

## 📁 Estructura del Proyecto

```
ventas-JeffersonS3-main/
├── config/
│   └── config.php              # Configuración global de la aplicación
├── models/
│   ├── Usuario.php             # Modelo de usuarios
│   ├── Producto.php            # Modelo de productos
│   └── Venta.php               # Modelo de ventas
├── controllers/
│   ├── LoginController.php      # Controlador de autenticación
│   ├── ProductoController.php   # Controlador de productos
│   ├── VentaController.php      # Controlador de ventas
│   └── DashboardController.php  # Controlador del dashboard
├── views/
│   ├── login.php               # Vista de login
│   ├── dashboard.php           # Vista del dashboard
│   ├── venta.php               # Vista de crear venta
│   ├── historial_ventas.php    # Vista del historial
│   ├── productos.php           # Vista del catálogo
│   └── includes/
│       └── navbar.php          # Componente de navegación
├── public/
│   ├── css/
│   │   └── style.css           # Estilos cyberpunk (todo en uno)
│   └── js/
│       ├── utils.js            # Funciones compartidas
│       ├── dashboard.js        # Script del dashboard
│       ├── venta.js            # Script de ventas
│       ├── historial.js        # Script del historial
│       └── productos.js        # Script del catálogo
├── index.php                   # Controlador frontal
├── .htaccess                   # Configuración de Apache
├── Dockerfile                  # Imagen Docker
├── docker-compose.yml          # Orquestación de contenedores
├── database.sql                # Script de base de datos
└── README.md                   # Este archivo
```

## 🗄️ Base de Datos

### Tablas Creadas:
- **usuarios** - Gestión de usuarios del sistema
- **productos** - Catálogo de 20 productos tecnológicos
- **ventas** - Registro de ventas con subtotal, IGV y total
- **detalles_venta** - Detalles de cada producto en la venta

### Usuario de Prueba:
```
Email: admin@ventas.com
Contraseña: admin123
```

### 20 Productos Incluidos:
- 3 Laptops (Dell, HP, Lenovo)
- 3 Monitores (LG, Dell, ASUS)
- 3 Teclados (Corsair, Logitech, Razer)
- 3 Mouses (Logitech, Razer, SteelSeries)
- 3 Audífonos (Sony, Bose, JBL)
- 2 Accesorios (Webcam, Micrófono, Docking)
- 1 Cable HDMI
- 1 Adaptador USB-C

## 💻 Requisitos

- Docker y Docker Compose
- Puerto 8080 disponible (para la aplicación)
- Puerto 3306 disponible (para MySQL)

## 🚀 Instalación y Ejecución

### 1. Detener cualquier contenedor anterior
```bash
docker-compose down
```

### 2. Construir e iniciar los contenedores
```bash
cd c:/xampp/htdocs/ventas-JeffersonS3-main
docker-compose up --build
```

### 3. Acceder a la aplicación
Una vez iniciado, abre tu navegador:
- **URL**: http://localhost:8080
- **Usuario**: admin@ventas.com
- **Contraseña**: admin123

### 4. Detener los contenedores
```bash
docker-compose down
```

## 🎨 Interfaz Cyberpunk

La interfaz incluye:
- **Colores Neon**: Cian (#00d9ff), Magenta (#ff006e), Dorado (#ffbe0b)
- **Animaciones Suaves**: Transiciones y efectos visuales
- **Responsive Design**: Se adapta a cualquier dispositivo
- **Modo Oscuro**: Tema oscuro por defecto
- **Fuentes Monoespacio**: Estilo retro-futurista

## 🔐 Características de Seguridad

1. **Prepared Statements** - Todas las consultas usan prepared statements
2. **Hash de Contraseñas** - SHA-256 para almacenar contraseñas
3. **Validación de Entrada** - Validación en servidor y cliente
4. **Autenticación de Sesión** - Control de acceso a páginas
5. **CORS Básico** - Encabezados de seguridad HTTP

## 📊 Funcionalidades Principales

### 1. Login
- Autenticación segura
- Manejo de sesiones
- Validación de credenciales

### 2. Dashboard
- Estadísticas en tiempo real
- Últimas ventas del día
- Total de productos
- Acciones rápidas

### 3. Nueva Venta
- Selección de productos de lista desplegable
- Carrito dinámico
- Cálculo automático de IGV (18%)
- Validación de stock
- Registro de cliente

### 4. Historial de Ventas
- Tabla de todas las ventas
- Búsqueda por cliente
- Filtro por fecha
- Ver detalles de venta
- Opción de imprimir

### 5. Catálogo de Productos
- Visualización de todos los productos
- Filtro por categoría
- Búsqueda por nombre
- Información de stock

## 🔧 APIs Disponibles

Todas las APIs retornan JSON:

### Productos
- `index.php?pagina=producto&accion=obtener_productos` - Todos los productos
- `index.php?pagina=producto&accion=obtener_categorias` - Categorías
- `index.php?pagina=producto&accion=obtener_por_categoria&categoria=Laptops` - Por categoría
- `index.php?pagina=producto&accion=obtener_producto&id=1` - Producto específico

### Ventas
- `index.php?pagina=venta&accion=crear` - Crear venta (POST)
- `index.php?pagina=venta&accion=obtener_ventas` - Todas las ventas
- `index.php?pagina=venta&accion=obtener_detalles&venta_id=1` - Detalles de venta
- `index.php?pagina=venta&accion=estadisticas` - Estadísticas

## 📝 Ejemplo de Creación de Venta

```javascript
// Request POST a: index.php?pagina=venta&accion=crear
{
    "cliente_nombre": "Juan Pérez",
    "cliente_email": "juan@example.com",
    "cliente_telefono": "987654321",
    "items": [
        {
            "producto_id": 1,
            "cantidad": 2,
            "precio_unitario": 1599.99
        }
    ]
}

// Response
{
    "exito": true,
    "mensaje": "Venta creada correctamente",
    "venta_id": 3,
    "total": 3799.96
}
```

## 🐛 Solución de Problemas

### Problema: "No se puede conectar a la base de datos"
**Solución**: Asegurar que MySQL está corriendo y la contraseña es "root"

### Problema: "Puertos en uso"
**Solución**: Cambiar puertos en docker-compose.yml:
```yaml
ports:
  - "8081:80"  # Puerto 8081 en host
  - "3307:3306"  # Puerto 3307 en host
```

### Problema: "Archivos no se guardan"
**Solución**: Los volúmenes de Docker mapean automáticamente, verificar permisos

## 📈 Mejoras Futuras

- [ ] Reportes en PDF
- [ ] Integración de pago
- [ ] Gestión de clientes
- [ ] Notificaciones por email
- [ ] Sistema de inventario avanzado
- [ ] Gráficos de estadísticas
- [ ] Multi-usuario con roles
- [ ] API REST completa

## 👨‍💻 Autor

Desarrollado como sistema profesional de gestión de ventas.

## 📄 Licencia

Este proyecto es de código abierto y se puede usar libremente.

## 📞 Soporte

Para reportar errores o sugerencias, contactar al desarrollador.

---

**Versión**: 2.0.0  
**Última actualización**: Mayo 2026
