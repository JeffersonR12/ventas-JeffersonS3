<?php
/**
 * Archivo principal de la aplicación
 * Controlador frontal (Front Controller)
 */

require_once __DIR__ . '/config/config.php';

// Verificar si el usuario está autenticado
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 'login';

// Páginas públicas
$publicas = ['login'];

// Verificar autenticación
if (!in_array($pagina, $publicas) && !isset($_SESSION['usuario_id'])) {
    header('Location: index.php?pagina=login');
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/js/utils.js"></script>
</head>
<body>
    <?php
    switch ($pagina) {
        case 'login':
            include 'controllers/LoginController.php';
            break;
            
        case 'dashboard':
            include 'controllers/DashboardController.php';
            break;
            
        case 'venta':
            include 'controllers/VentaController.php';
            break;
            
        case 'historial':
            include 'controllers/VentaController.php';
            $ventaController = new VentaController();
            $ventaController->mostrarHistorial();
            break;
            
        case 'productos':
            include 'controllers/ProductoController.php';
            break;
            
        case 'producto':
            include 'controllers/ProductoController.php';
            break;
            
        default:
            header('Location: index.php?pagina=dashboard');
            break;
    }
    ?>
</body>
</html>
