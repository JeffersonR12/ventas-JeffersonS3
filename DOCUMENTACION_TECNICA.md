# 📚 Documentación Técnica Detallada

## 1. Arquitectura MVC Explicada

### Modelos (Models)
Los modelos representan la capa de datos y lógica empresarial:

- **Usuario.php**
  - Maneja operaciones CRUD de usuarios
  - Valida credenciales con hash SHA-256
  - Gestiona rol y estado de usuario

- **Producto.php**
  - Obtiene productos activos del catálogo
  - Filtra por categoría
  - Verifica disponibilidad de stock
  - Actualiza inventario

- **Venta.php**
  - Crea ventas con transacciones
  - Calcula automáticamente subtotal e IGV
  - Gestiona detalles de venta
  - Revierte cambios en caso de error

### Controladores (Controllers)
Procesan las peticiones y coordinan modelos con vistas:

- **LoginController.php**
  - Autentica usuarios
  - Gestiona sesiones
  - Procesa logout

- **ProductoController.php**
  - Retorna productos en JSON
  - Filtra por categoría
  - Proporciona datos para los selects

- **VentaController.php**
  - Crea nuevas ventas
  - Valida datos del cliente
  - Calcula totales correctamente
  - Retorna historial de ventas

- **DashboardController.php**
  - Obtiene estadísticas
  - Carga ventas del día
  - Información de productos

### Vistas (Views)
HTML + CSS para la interfaz:

- **login.php** - Formulario de autenticación
- **dashboard.php** - Panel principal
- **venta.php** - Crear nueva venta
- **historial_ventas.php** - Registros anteriores
- **productos.php** - Catálogo
- **navbar.php** - Componente de navegación

## 2. Flujo de Cálculo de Ventas

### Proceso Correcto:

```
1. Usuario selecciona producto → Se obtiene precio unitario de BD
2. Usuario ingresa cantidad
3. Se calcula: Subtotal Item = Precio Unitario × Cantidad
4. Se suma a Subtotal Total
5. Se calcula: IGV = Subtotal Total × 0.18
6. Se calcula: Total = Subtotal + IGV
7. Se registra en BD con estos valores exactos
```

### Ejemplo Real:
```
Laptop Dell: $1,599.99 × 1 = $1,599.99
Mouse Logitech: $99.99 × 2 = $199.98

Subtotal: $1,799.97
IGV (18%): $323.99
Total: $2,123.96
```

## 3. Seguridad: Prepared Statements

### ❌ Vulnerable (Viejo código):
```php
$sql = "INSERT INTO ventas VALUES ('$cliente', '$producto', '$cantidad')";
mysqli_query($conexion, $sql); // ¡Vulnerable a SQL Injection!
```

### ✅ Seguro (Nuevo código):
```php
$query = "INSERT INTO ventas (usuario_id, cliente_nombre, cantidad) 
          VALUES (?, ?, ?)";
$stmt = $this->conexion->prepare($query);
$stmt->bind_param("isi", $usuario_id, $cliente_nombre, $cantidad);
$stmt->execute(); // ¡Seguro!
```

**Ventajas**:
- Previene SQL Injection
- Valores separados de código SQL
- Auto-escapado de caracteres especiales

## 4. Flujo de Crear Venta

### Frontend (venta.js):
```javascript
// 1. Usuario selecciona producto → Carga precio desde BD
// 2. Usuario ingresa cantidad → Calcula subtotal item
// 3. Usuario hace click "Agregar" → Valida stock
// 4. Producto se agrega al carrito
// 5. Se actualiza resumen (Subtotal + IGV + Total)
// 6. Usuario hace click "Registrar" → Envía JSON al servidor
```

### Backend (VentaController.php):
```php
// 1. Recibe JSON con items del carrito
// 2. Valida datos del cliente (nombre requerido)
// 3. Itera cada producto:
//    - Obtiene precio actual de BD
//    - Verifica stock disponible
//    - Suma al subtotal
// 4. Calcula: IGV = Subtotal × 0.18
// 5. Calcula: Total = Subtotal + IGV
// 6. Inicia transacción:
//    - Inserta venta en tabla ventas
//    - Inserta detalles en tabla detalles_venta
//    - Actualiza stock de productos
// 7. Confirma transacción o revierte si hay error
// 8. Retorna venta_id y total
```

### Ventaja de Transacciones:
Si algo falla (ej: stock insuficiente), TODO se revierte:
- La venta no se crea
- Los detalles no se guardan
- El stock no se actualiza

## 5. Autenticación y Sesiones

### Hash SHA-256:
```php
// Guardar contraseña:
$contraseña_hash = hash('sha256', 'admin123');
// Resultado: 240f8400e3d21eec41a65acbf822346c257c396afc0eba

// Validar contraseña:
if (hash('sha256', $contraseña_ingresada) === $contraseña_guardada) {
    // ✅ Correcto
}
```

### Gestión de Sesiones:
```php
// Login exitoso:
$_SESSION['usuario_id'] = 1;
$_SESSION['usuario_nombre'] = 'Administrador';
$_SESSION['usuario_rol'] = 'admin';

// Verificar autenticación:
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir a login
}

// Logout:
session_destroy();
```

## 6. Estructura de la Base de Datos

### Tabla: usuarios
```sql
id              INT PRIMARY KEY AUTO_INCREMENT
nombre          VARCHAR(100)
email           VARCHAR(100) UNIQUE
contraseña      VARCHAR(255) [SHA-256 hash]
rol             VARCHAR(50) [admin|vendedor]
estado          VARCHAR(20) [activo|inactivo]
fecha_creacion  TIMESTAMP
```

### Tabla: productos
```sql
id              INT PRIMARY KEY AUTO_INCREMENT
nombre          VARCHAR(150)
descripcion     TEXT
categoria       VARCHAR(50)
precio_unitario DECIMAL(10, 2)
stock           INT
estado          VARCHAR(20)
fecha_creacion  TIMESTAMP
```

### Tabla: ventas
```sql
id              INT PRIMARY KEY AUTO_INCREMENT
usuario_id      INT FOREIGN KEY → usuarios(id)
cliente_nombre  VARCHAR(100)
cliente_email   VARCHAR(100)
cliente_telefono VARCHAR(20)
subtotal        DECIMAL(10, 2)
igv             DECIMAL(10, 2)
total           DECIMAL(10, 2)
estado          VARCHAR(20)
fecha_venta     TIMESTAMP
```

### Tabla: detalles_venta
```sql
id              INT PRIMARY KEY AUTO_INCREMENT
venta_id        INT FOREIGN KEY → ventas(id)
producto_id     INT FOREIGN KEY → productos(id)
cantidad        INT
precio_unitario DECIMAL(10, 2)
subtotal        DECIMAL(10, 2)
```

## 7. APIs y Endpoints

### Estructura de URLs:
```
index.php?pagina=SECCION&accion=ACCION&param=VALOR
```

### Ejemplos:

#### Obtener productos
```
GET: index.php?pagina=producto&accion=obtener_productos
Response: [
    {"id":1, "nombre":"Laptop Dell XPS 15", "precio_unitario":1599.99, ...},
    ...
]
```

#### Obtener categorías
```
GET: index.php?pagina=producto&accion=obtener_categorias
Response: ["Laptops", "Monitores", "Teclados", ...]
```

#### Crear venta
```
POST: index.php?pagina=venta&accion=crear
Body: {
    "cliente_nombre": "Juan Pérez",
    "cliente_email": "juan@example.com",
    "cliente_telefono": "987654321",
    "items": [
        {"producto_id": 1, "cantidad": 2}
    ]
}
Response: {
    "exito": true,
    "mensaje": "Venta creada correctamente",
    "venta_id": 3,
    "total": 2123.96
}
```

## 8. Estilos Cyberpunk

### Paleta de Colores:
```css
--primary: #00d9ff      /* Cian neon */
--secondary: #ff006e    /* Magenta neon */
--accent: #ffbe0b       /* Dorado */
--dark-bg: #0a0e27      /* Fondo oscuro */
--success: #00d084      /* Verde éxito */
--danger: #ff4757       /* Rojo peligro */
```

### Efectos Visuales:
- **Glow**: Sombras con colores neon
- **Animaciones**: Smooth transitions y keyframes
- **Gradientes**: Combinaciones de colores
- **Hover**: Estados interactivos

## 9. Validaciones Implementadas

### Cliente:
```javascript
// En venta.js
1. Producto seleccionado
2. Cantidad > 0
3. Stock disponible
4. Nombre de cliente (requerido)
5. Cantidad debe ser número
```

### Servidor:
```php
// En VentaController.php
1. Usuario autenticado
2. Datos JSON válidos
3. Nombre cliente no vacío
4. Items no vacío
5. Producto existe
6. Stock suficiente para cada item
7. Precio válido
```

## 10. Transacciones en MySQL

### Garantizan Consistencia:

```php
// Inicia transacción
$this->conexion->begin_transaction();

try {
    // 1. Inserta venta
    // 2. Inserta detalles
    // 3. Actualiza stock
    
    // Si todo OK:
    $this->conexion->commit();
    
} catch (Exception $e) {
    // Si hay error:
    $this->conexion->rollback();
    throw $e;
}
```

**Ejemplo**: Si falla actualizar stock, TODO se revierte.

## 11. Manejo de Errores

### Respuestas JSON Estructuradas:

```javascript
// Success
{
    "exito": true,
    "mensaje": "Venta creada correctamente",
    "venta_id": 3
}

// Error
{
    "exito": false,
    "mensaje": "Stock insuficiente para: Laptop"
}
```

### Códigos HTTP:
- `200` - OK
- `400` - Bad Request (error de validación)
- `404` - Not Found
- `500` - Server Error

## 12. Optimizaciones Aplicadas

1. **Indexes en BD**: Campos frecuentemente buscados
2. **Lazy Loading**: Productos se cargan bajo demanda
3. **Caché en JS**: Productos originales en memoria
4. **Prepared Statements**: Más rápido que concatenar strings
5. **CSS sin framework**: Más ligero y personalizado

## 13. Configuración de Docker

### Variables de Entorno:
```dockerfile
DB_HOST=db              # Nombre del servicio MySQL
DB_USER=root            # Usuario MySQL
DB_PASS=root            # Contraseña
DB_NAME=ventas_db       # Nombre BD
```

### Volúmenes:
```yaml
volumes:
  mysql_data: {}  # Persiste datos de MySQL entre reinicios
  .:/var/www/html # Sincroniza código local con contenedor
```

### Health Check:
```yaml
healthcheck:
  test: ["CMD", "mysqladmin", "ping"]  # Verifica MySQL está listo
  timeout: 20s
  retries: 10
```

## 14. Extensiones PHP Utilizadas

```dockerfile
docker-php-ext-install pdo pdo_mysql zip
```

- **pdo** - PHP Data Objects (acceso a BD)
- **pdo_mysql** - Driver MySQL para PDO
- **zip** - Compresión de archivos

## 15. Mejores Prácticas Implementadas

✅ **Separación de Capas** - MVC completo  
✅ **DRY** - Don't Repeat Yourself (funciones reutilizables)  
✅ **SOLID** - Responsabilidad única de cada clase  
✅ **Prepared Statements** - SQL Injection seguro  
✅ **Session Management** - Control de acceso  
✅ **Error Handling** - Try-catch en transacciones  
✅ **Validación Doble** - Cliente y servidor  
✅ **Responsive Design** - Mobile-first  
✅ **Accesibilidad** - Labels y structure adecuados  
✅ **Documentación** - Comentarios en código  

---

**Versión**: 2.0.0  
**Última actualización**: Mayo 2026
