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

// Función para crear el esquema si falta alguna tabla clave
function verificarEsquemaBD($conexion) {
    $resultado = $conexion->query("SHOW TABLES LIKE 'usuarios'");
    if ($resultado === false || $resultado->num_rows === 0) {
        $rutaSql = __DIR__ . '/../database.sql';
        if (!file_exists($rutaSql)) {
            throw new Exception('Archivo de inicialización de base de datos no encontrado: ' . $rutaSql);
        }

        $sql = file_get_contents($rutaSql);
        if ($sql === false) {
            throw new Exception('No se pudo leer el archivo de inicialización de la base de datos.');
        }

        if (!$conexion->multi_query($sql)) {
            throw new Exception('Error al inicializar la base de datos: ' . $conexion->error);
        }

        // Consumir todos los resultados restantes para evitar bloqueos
        while ($conexion->more_results() && $conexion->next_result()) {
            // no-op
        }
    }
}

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

        // Verificar que el esquema de la base de datos exista y crear tablas si faltan.
        verificarEsquemaBD($conn);
        
        return $conn;
    } catch (Exception $e) {
        die('Error de conexión a la base de datos: ' . $e->getMessage());
    }
}

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
