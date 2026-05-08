# 🚀 GUÍA RÁPIDA DE EJECUCIÓN

## Pasos para ejecutar el sistema completo en Docker

### 1️⃣ Prerequisitos
- Tener Docker y Docker Compose instalado
- Puertos 8080 y 3306 disponibles

### 2️⃣ Ejecutar en Terminal

```bash
# Ir al directorio del proyecto
cd c:\xampp\htdocs\ventas-JeffersonS3-main

# Detener contenedores anteriores (si existen)
docker-compose down

# Construir e iniciar
docker-compose up --build
```

### 3️⃣ Esperar a que inicie

Verás en la consola:
```
db service_healthy
✔ Container ventas_db Started
✔ Container ventas_app Started
```

### 4️⃣ Acceder a la aplicación

**URL**: http://localhost:8080

### 5️⃣ Credenciales de Prueba

```
Email:     admin@ventas.com
Password:  admin123
```

### 6️⃣ Usar el Sistema

#### Dashboard
- Ver estadísticas del día
- Últimas 5 ventas
- Acciones rápidas

#### Nueva Venta
1. Ingresa nombre del cliente
2. Selecciona categoría → producto
3. Ingresa cantidad
4. Click en "Agregar Producto"
5. Verifica resumen (subtotal, IGV, total)
6. Click en "Registrar Venta"

#### Historial
- Ver todas las ventas
- Buscar por cliente
- Filtrar por fecha
- Ver detalles

#### Productos
- Catálogo completo
- Buscar por nombre
- Filtrar por categoría

### 7️⃣ Detener el Sistema

```bash
# En la terminal donde corre docker-compose
Ctrl + C

# O ejecutar
docker-compose down
```

---

## 📊 Datos de Prueba Incluidos

**2 Ventas Previas en la BD**:

| Cliente | Producto | Monto | Fecha |
|---------|----------|-------|-------|
| Juan Pérez | Laptop Dell | S/ 1,887.98 | Hoy |
| María García | Mouses | S/ 353.97 | Hoy |

**20 Productos Listos**:
- Laptops, Monitores, Teclados, Mouses, Audífonos, Accesorios

---

## 🔧 Solución de Problemas Rápida

### Puerto 8080 en uso
```bash
# Cambiar puerto en docker-compose.yml
ports:
  - "8081:80"  # Cambiar 8080 a 8081
```

### Puerto 3306 en uso
```bash
# Cambiar puerto en docker-compose.yml
ports:
  - "3307:3306"  # Cambiar 3306 a 3307
```

### Ver logs
```bash
docker-compose logs -f app
docker-compose logs -f db
```

### Reiniciar
```bash
docker-compose restart
```

---

## 📁 Archivos Clave

| Archivo | Propósito |
|---------|-----------|
| `index.php` | Punto de entrada principal |
| `database.sql` | Datos iniciales |
| `config/config.php` | Configuración global |
| `public/css/style.css` | Estilos cyberpunk |
| `public/js/*.js` | Lógica frontend |

---

## ✅ Checklist de Verificación

- [ ] Docker corre correctamente
- [ ] Puertos 8080 y 3306 están libres
- [ ] Base de datos se inicializa automáticamente
- [ ] Login funciona con admin@ventas.com / admin123
- [ ] Dashboard muestra estadísticas
- [ ] Se puede crear una venta
- [ ] El historial registra nuevas ventas
- [ ] Los cálculos son correctos (subtotal + IGV = total)

---

## 🎯 Próximas Acciones

1. ✅ Sistema funcionando en Docker
2. ✅ Base de datos con datos iniciales
3. ✅ Interfaz cyberpunk lista
4. 📝 Personalizar datos de empresa
5. 📝 Configurar email
6. 📝 Agregar más productos

---

**¡Sistema listo para usar! 🎉**
