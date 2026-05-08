# 🗂️ ESTRUCTURA COMPLETA DEL PROYECTO

## Vista General del Proyecto

```
ventas-JeffersonS3-main/
├── 📄 index.php                    # ← PUNTO DE ENTRADA PRINCIPAL
├── 📄 .htaccess                    # Configuración Apache
├── 📄 Dockerfile                   # Imagen Docker
├── 📄 docker-compose.yml           # Orquestación
├── 📄 database.sql                 # Base de datos
│
├── 📁 config/
│   └── 📄 config.php               # ← CONFIGURACIÓN GLOBAL
│
├── 📁 models/                      # ← CAPA DE DATOS
│   ├── 📄 Usuario.php              # CRUD de usuarios
│   ├── 📄 Producto.php             # CRUD de productos
│   └── 📄 Venta.php                # CRUD de ventas (con transacciones)
│
├── 📁 controllers/                 # ← CAPA DE LÓGICA
│   ├── 📄 LoginController.php      # Autenticación
│   ├── 📄 ProductoController.php   # Productos (APIs)
│   ├── 📄 VentaController.php      # Ventas (APIs)
│   └── 📄 DashboardController.php  # Dashboard
│
├── 📁 views/                       # ← CAPA DE PRESENTACIÓN
│   ├── 📄 login.php                # Pantalla login
│   ├── 📄 dashboard.php            # Pantalla principal
│   ├── 📄 venta.php                # Crear venta
│   ├── 📄 historial_ventas.php    # Historial
│   ├── 📄 productos.php            # Catálogo
│   └── 📁 includes/
│       └── 📄 navbar.php           # Navegación
│
├── 📁 public/
│   ├── 📁 css/
│   │   └── 📄 style.css            # ← ESTILOS CYBERPUNK (650+ líneas)
│   │
│   └── 📁 js/
│       ├── 📄 utils.js             # Funciones compartidas
│       ├── 📄 dashboard.js         # Script dashboard
│       ├── 📄 venta.js             # Script crear venta
│       ├── 📄 historial.js         # Script historial
│       └── 📄 productos.js         # Script productos
│
├── 📁 tests/
│   └── 📄 VentaTest.php
│
└── 📁 vendor/                      # Dependencias PHP
    └── autoload.php
```

---

## Flujo de la Aplicación

```
┌─────────────────────────────────────────────────────────────┐
│                    USUARIO ACCEDE                            │
│              http://localhost:8080                           │
└─────────────────────────────────────────────────────────────┘
                             ↓
                    ┌────────────────┐
                    │ index.php      │
                    │ (Front Router) │
                    └────────────────┘
                             ↓
            ┌────────────────────────────────────┐
            │  ¿Está autenticado?                │
            └────────────────────────────────────┘
                    ↓               ↓
                   NO              SI
                    ↓               ↓
            ┌─────────────┐  ┌────────────────┐
            │ LoginPage   │  │ ¿Qué página?   │
            └─────────────┘  └────────────────┘
                    ↓          ↓    ↓    ↓    ↓
              [Login]     [DAS] [VTA] [HIS] [PRO]
                    ↓          ↓    ↓    ↓    ↓
            ┌─────────────┐  ┌────────────────┐
            │Controller   │  │Controller      │
            │(Validar)    │  │(Procesar)      │
            └─────────────┘  └────────────────┘
                    ↓          ↓    ↓    ↓    ↓
            ┌─────────────┐  ┌────────────────┐
            │Model        │  │Model           │
            │(BD)         │  │(BD)            │
            └─────────────┘  └────────────────┘
                    ↓          ↓    ↓    ↓    ↓
            ┌─────────────┐  ┌────────────────┐
            │Vista        │  │Vista           │
            │(HTML)       │  │(HTML)          │
            └─────────────┘  └────────────────┘
                    ↓          ↓    ↓    ↓    ↓
              [Renderizar]    [JSON APIs]
                    ↓          ↓    ↓    ↓    ↓
            ┌─────────────────────────────────┐
            │     NAVEGADOR (HTML + JS)       │
            └─────────────────────────────────┘
```

---

## Ciclo de Crear una Venta

```
USUARIO                    NAVEGADOR (JS)           SERVIDOR (PHP)              BASE DE DATOS
   │                           │                           │                          │
   │──[1. Login]──────────────→│                           │                          │
   │                           │───[Validar]──────────────→│                          │
   │                           │                           │──[query]────────────────→│
   │                           │                           │←[usuario]────────────────│
   │←[Session OK]──────────────│←[Redirect]────────────────│                          │
   │                           │                           │                          │
   │──[2. Nueva Venta]────────→│                           │                          │
   │                           │──[GET productos]─────────→│                          │
   │                           │←[JSON array]──────────────│──[SELECT]────────────────→│
   │                           │←[Cargar dropdown]────────→│←[20 productos]──────────│
   │                           │                           │                          │
   │──[3. Selecciona producto]→│                           │                          │
   │                           │──[Obtener precio]────────→│                          │
   │                           │←[Precio + stock]─────────│──[SELECT]────────────────→│
   │                           │←[Mostrar en form]────────→│←[precio, stock]─────────│
   │                           │                           │                          │
   │──[4. Ingresa cantidad]───→│                           │                          │
   │                           │──[Validar stock]─────────→│                          │
   │──[5. Agregar al carrito]→│                           │                          │
   │                           │──[Calcular totales]──────→│                          │
   │                           │←[Actualizar resumen]─────│                          │
   │                           │                           │                          │
   │──[6. Registrar Venta]────→│                           │                          │
   │                           │──[POST JSON]────────────→│──[BEGIN TRANSACTION]────→│
   │                           │                           │──[INSERT ventas]────────→│
   │                           │                           │──[INSERT detalles]──────→│
   │                           │                           │──[UPDATE stock]─────────→│
   │                           │                           │──[COMMIT]───────────────→│
   │                           │                           │←[venta_id]──────────────│
   │←[Venta exitosa]───────────│←[JSON success]───────────│                          │
   │                           │                           │                          │
   │──[7. Ver Historial]──────→│                           │                          │
   │                           │──[GET historial]─────────→│──[SELECT ventas]────────→│
   │                           │←[JSON array]──────────────│←[todas las ventas]──────│
   │←[Tabla actualizada]───────│←[Renderizar tabla]───────│                          │
```

---

## Arquitectura MVC Detallada

### MODELO (Model)

```
Usuario.php
├── obtenerPorEmail(email)
├── crear(nombre, email, contraseña)
├── validarCredenciales(email, contraseña)
├── obtenerTodos()
├── obtenerPorId(id)
├── actualizar(id, ...)
└── eliminar(id)

Producto.php
├── obtenerActivos()
├── obtenerPorCategoria(categoria)
├── obtenerPorId(id)
├── obtenerCategorias()
├── verificarStock(id, cantidad)
├── actualizarStock(id, cantidad)
└── crear/actualizar/eliminar()

Venta.php
├── crear(usuario_id, cliente, items, subtotal, igv)
│   ├── [BEGIN TRANSACTION]
│   ├── INSERT ventas
│   ├── INSERT detalles_venta (por cada item)
│   ├── UPDATE stock (por cada item)
│   ├── [COMMIT] o [ROLLBACK]
├── obtenerTodas()
├── obtenerPorId(id)
├── obtenerDetalles(venta_id)
├── obtenerEstadisticas()
└── actualizarEstado/eliminar()
```

### CONTROLADOR (Controller)

```
LoginController
├── mostrarFormulario() → render login.php
├── procesar() → validar credenciales
└── logout() → limpiar sesión

ProductoController
├── mostrarProductos() → render productos.php
├── obtenerProductosJSON() → { JSON }
├── obtenerCategoriasJSON() → { JSON }
└── obtenerPorCategoriaJSON(cat) → { JSON }

VentaController
├── mostrarFormulario() → render venta.php
├── crearVenta() → POST → { JSON }
├── obtenerVentasJSON() → { JSON }
├── obtenerDetallesJSON(id) → { JSON }
├── mostrarHistorial() → render historial.php
└── obtenerEstadisticasJSON() → { JSON }

DashboardController
└── mostrar() → render dashboard.php
```

### VISTA (View)

```
login.php
├── <form> Login
├── Email input
├── Password input
└── Submit button

dashboard.php
├── <navbar>
├── Stats Grid
│   ├── Ventas hoy
│   ├── Ingresos
│   ├── Productos
│   └── Clientes
├── Últimas ventas
└── Acciones rápidas

venta.php
├── <navbar>
├── Form cliente
│   ├── Nombre*
│   ├── Email
│   └── Teléfono
├── Form productos
│   ├── Categoría
│   ├── Producto*
│   ├── Cantidad*
│   └── Precio
├── Carrito dinámico
└── Resumen totales

historial_ventas.php
├── <navbar>
├── Filtros
│   ├── Buscar cliente
│   └── Filtrar fecha
├── Tabla ventas
│   ├── ID
│   ├── Cliente
│   ├── Vendedor
│   ├── Subtotal
│   ├── IGV
│   ├── Total
│   ├── Fecha
│   ├── Estado
│   └── Acciones
└── Modal detalles

productos.php
├── <navbar>
├── Filtros
│   ├── Buscar
│   └── Categoría
└── Grid de productos
    ├── Imagen (emoji)
    ├── Categoría
    ├── Nombre
    ├── Descripción
    ├── Precio
    └── Stock
```

---

## Base de Datos

```
┌──────────────────────────┐
│      USUARIOS (4)        │
├──────────────────────────┤
│ id (PK)                  │
│ nombre (VARCHAR 100)     │
│ email (VARCHAR 100) UNQ  │
│ contraseña (VARCHAR 255) │ SHA-256
│ rol (VARCHAR 50)         │ admin|vendedor
│ estado (VARCHAR 20)      │ activo|inactivo
│ fecha_creacion (TS)      │
└──────────────────────────┘
         │
         │ FK
         ↓
┌──────────────────────────┐
│   VENTAS (2+)            │
├──────────────────────────┤
│ id (PK)                  │
│ usuario_id (FK)         │ ← USUARIOS
│ cliente_nombre (VARCHAR) │
│ cliente_email (VARCHAR)  │
│ cliente_telefono (VAR)   │
│ subtotal (DECIMAL)       │
│ igv (DECIMAL)            │ 18%
│ total (DECIMAL)          │
│ estado (VARCHAR 20)      │ pendiente|completado
│ fecha_venta (TS)         │
└──────────────────────────┘
         │
         │ FK (1:N)
         ↓
┌──────────────────────────┐
│  DETALLES_VENTA (3+)     │
├──────────────────────────┤
│ id (PK)                  │
│ venta_id (FK) CASCADE    │
│ producto_id (FK)        │ ← PRODUCTOS
│ cantidad (INT)           │
│ precio_unitario (DEC)    │
│ subtotal (DECIMAL)       │
└──────────────────────────┘
                 │
                 │ FK
                 ↓
┌──────────────────────────┐
│    PRODUCTOS (20)        │
├──────────────────────────┤
│ id (PK)                  │
│ nombre (VARCHAR 150)     │
│ descripcion (TEXT)       │
│ categoria (VARCHAR 50)   │
│ precio_unitario (DEC)    │
│ stock (INT)              │
│ estado (VARCHAR 20)      │
│ fecha_creacion (TS)      │
└──────────────────────────┘
```

---

## Flujo de Archivos CSS y JS

```
REQUEST
   ↓
index.php
   ├── load config/config.php
   ├── load public/css/style.css (650+ líneas)
   └── load appropriate controller
       ├── views/login.php
       │   └── (inline HTML)
       ├── views/dashboard.php
       │   ├── views/includes/navbar.php
       │   └── <script> public/js/dashboard.js
       ├── views/venta.php
       │   ├── views/includes/navbar.php
       │   ├── <script> public/js/venta.js
       │   └── <script> public/js/utils.js
       ├── views/historial_ventas.php
       │   ├── views/includes/navbar.php
       │   ├── <script> public/js/historial.js
       │   └── <script> public/js/utils.js
       └── views/productos.php
           ├── views/includes/navbar.php
           ├── <script> public/js/productos.js
           └── <script> public/js/utils.js
```

---

## Validaciones en el Sistema

```
┌─── CLIENTE (JavaScript) ───┐
│ • Nombre cliente requerido │
│ • Cantidad > 0             │
│ • Stock suficiente         │
│ • Producto seleccionado    │
│ • Email formato válido     │
└────────────────────────────┘
         ↓
    Envía JSON
         ↓
┌─── SERVIDOR (PHP) ───┐
│ • Usuario autenticado│
│ • JSON válido        │
│ • Datos no vacíos    │
│ • Producto existe    │
│ • Stock verificado   │
│ • Precio actual      │
│ • Cálculo correcto   │
└──────────────────────┘
         ↓
    BD con
    Transacciones
         ↓
┌─ RESPUESTA JSON ───┐
│ • exito: true/false│
│ • mensaje: string  │
│ • venta_id: número │
│ • total: decimal   │
└────────────────────┘
```

---

## Resumen de Tecnologías

```
FRONTEND
├── HTML5 (Semántico)
├── CSS3 (Cyberpunk custom)
│   ├── Flexbox / Grid
│   ├── Animaciones
│   ├── Gradientes
│   └── Responsive
├── JavaScript (Vanilla)
│   ├── Fetch API
│   ├── DOM Manipulation
│   └── Event Listeners
└── JSON (APIs)

BACKEND
├── PHP 8.1
│   ├── OOP (Clases)
│   ├── MySQLi
│   ├── Prepared Statements
│   ├── Transacciones
│   └── Sessions
├── MySQL 8.0
│   ├── 4 Tablas
│   ├── Claves Foráneas
│   ├── Indexes
│   └── ON DELETE CASCADE
└── Apache 2.4
    ├── mod_rewrite
    ├── mod_headers
    └── mod_deflate

DEVOPS
├── Docker
│   ├── Dockerfile
│   ├── docker-compose.yml
│   ├── Volumes
│   ├── Networks
│   └── Health Checks
└── .htaccess
    ├── Rewrite Rules
    ├── Security Headers
    └── Gzip Compression
```

---

**Arquitectura profesional, segura y escalable. ✨**
