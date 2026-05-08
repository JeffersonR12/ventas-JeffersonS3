<?php
/**
 * Configuración global de la aplicación
 */

// Configuración de base de datos
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: 'root');
define('DB_NAME', getenv('DB_NAME') ?: 'ventas_db');
define('DB_PORT', 3306);

// Configuración de la aplicación
define('APP_NAME', 'Sistema de Ventas Tecnológico');
define('APP_VERSION', '2.0.0');
define('APP_URL', 'http://localhost:8080');

// Configuración de sesión
define('SESSION_TIMEOUT', 3600); // 1 hora en segundos
define('SESSION_NAME', 'ventas_session');

// Tasa de IGV (18% para Perú)
define('IGV_RATE', 0.18);

// Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Función para conectar a la base de datos
function conectarBD() {
    try {
        $conn = mysqli_connect(
            DB_HOST,
            DB_USER,
            DB_PASS,
            DB_NAME,
            DB_PORT
        );
        
        if (!$conn) {
            throw new Exception('Error de conexión: ' . mysqli_connect_error());
        }
        
        // Configurar charset UTF-8
        mysqli_set_charset($conn, "utf8mb4");
        
        return $conn;
    } catch (Exception $e) {
        die('Error de conexión a la base de datos: ' . $e->getMessage());
    }
}

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
