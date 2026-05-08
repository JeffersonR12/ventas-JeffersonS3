-- =====================================================
-- SISTEMA DE VENTAS - BASE DE DATOS
-- =====================================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS ventas_db;
USE ventas_db;

-- =====================================================
-- TABLA: usuarios
-- =====================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    rol VARCHAR(50) DEFAULT 'vendedor',
    estado VARCHAR(20) DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_rol (rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: productos
-- =====================================================
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    categoria VARCHAR(50) NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    estado VARCHAR(20) DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_categoria (categoria),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: ventas
-- =====================================================
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    cliente_nombre VARCHAR(100) NOT NULL,
    cliente_email VARCHAR(100),
    cliente_telefono VARCHAR(20),
    subtotal DECIMAL(10, 2) NOT NULL,
    igv DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    estado VARCHAR(20) DEFAULT 'pendiente',
    fecha_venta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_fecha (fecha_venta),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLA: detalles_venta
-- =====================================================
CREATE TABLE IF NOT EXISTS detalles_venta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    INDEX idx_venta (venta_id),
    INDEX idx_producto (producto_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERTAR USUARIO ADMIN
-- =====================================================
INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES
('Administrador', 'admin@ventas.com', SHA2('admin123', 256), 'admin');

-- =====================================================
-- INSERTAR 20 PRODUCTOS TECNOLÓGICOS
-- =====================================================
INSERT INTO productos (nombre, descripcion, categoria, precio_unitario, stock) VALUES
('Laptop Dell XPS 15', 'Laptop de alto rendimiento para profesionales', 'Laptops', 1599.99, 15),
('Laptop HP Pavilion 15', 'Laptop versátil para uso general', 'Laptops', 799.99, 20),
('Laptop Lenovo ThinkPad', 'Laptop empresarial robusta', 'Laptops', 1299.99, 10),
('Monitor LG UltraWide 34"', 'Monitor ultraancho para productividad', 'Monitores', 899.99, 8),
('Monitor Dell S2721DGF', 'Monitor gaming de 144Hz', 'Monitores', 499.99, 12),
('Monitor ASUS ProArt 32"', 'Monitor profesional 4K', 'Monitores', 1299.99, 5),
('Teclado Mecánico Corsair K95', 'Teclado gaming mecánico RGB', 'Teclados', 199.99, 25),
('Teclado Logitech MX Keys', 'Teclado inalámbrico para productividad', 'Teclados', 149.99, 30),
('Teclado Razer Huntsman V2', 'Teclado gaming de baja latencia', 'Teclados', 229.99, 18),
('Mouse Logitech MX Master 3', 'Mouse inalámbrico profesional', 'Mouses', 99.99, 35),
('Mouse Razer DeathAdder V3', 'Mouse gaming ultraligero', 'Mouses', 79.99, 40),
('Mouse SteelSeries Rival 5', 'Mouse gaming ergonómico', 'Mouses', 59.99, 50),
('Audífonos Sony WH-1000XM5', 'Audífonos con cancelación de ruido', 'Audífonos', 399.99, 12),
('Audífonos Bose QuietComfort 45', 'Audífonos premium con ANC', 'Audífonos', 379.99, 10),
('Audífonos JBL Tune 750TBNC', 'Audífonos deportivos con cancelación', 'Audífonos', 199.99, 22),
('Webcam Logitech 4K Pro', 'Webcam 4K para streaming', 'Accesorios', 179.99, 14),
('Micrófono Blue Yeti X', 'Micrófono USB profesional', 'Accesorios', 159.99, 16),
('Docking Station Thunderbolt 3', 'Estación de acoplamiento premium', 'Accesorios', 259.99, 8),
('Cable HDMI 2.1 Certificado', 'Cable HDMI de alta velocidad', 'Cables', 29.99, 100),
('Adaptador USB-C a HDMI', 'Adaptador multifunción tipo C', 'Adaptadores', 49.99, 45);

-- =====================================================
-- DATOS DE EJEMPLO (Ventas Históricas)
-- =====================================================
INSERT INTO ventas (usuario_id, cliente_nombre, cliente_email, cliente_telefono, subtotal, igv, total, estado) VALUES
(1, 'Juan Pérez', 'juan@example.com', '987654321', 1599.99, 287.99, 1887.98, 'completado'),
(1, 'María García', 'maria@example.com', '987654322', 299.98, 53.99, 353.97, 'completado');

INSERT INTO detalles_venta (venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES
(1, 1, 1, 1599.99, 1599.99),
(2, 10, 2, 99.99, 199.98),
(2, 15, 1, 99.99, 99.99);
