-- =====================================================
-- BASE DE DATOS: Sistema de Ventas
-- =====================================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS ventas_db;
USE ventas_db;

-- =====================================================
-- TABLA: ventas
-- =====================================================
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente VARCHAR(100) NOT NULL,
    producto VARCHAR(100) NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_cliente (cliente),
    INDEX idx_fecha (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DATOS DE EJEMPLO (Opcional)
-- =====================================================
INSERT INTO ventas (cliente, producto, cantidad, precio, total) VALUES
('Juan Pérez', 'Laptop', 1, 1500.00, 1500.00),
('María García', 'Mouse', 5, 25.00, 125.00),
('Carlos López', 'Teclado', 3, 50.00, 150.00);
