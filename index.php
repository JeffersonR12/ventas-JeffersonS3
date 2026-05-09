<?php
/**
 * Front controller de la aplicación
 */

session_start();
require_once __DIR__ . '/config/config.php';

$pagina = isset($_GET['pagina']) ? trim($_GET['pagina']) : 'login';
$pagina = preg_replace('/[^a-zA-Z0-9_]/', '', $pagina);

$publicPages = ['login'];
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !in_array($pagina, $publicPages, true) && !isset($_SESSION['usuario_id'])) {
    header('Location: index.php?pagina=login');
    exit;
}

switch ($pagina) {
    case 'dashboard':
        require_once __DIR__ . '/controllers/DashboardController.php';
        break;
    case 'venta':
        require_once __DIR__ . '/controllers/VentaController.php';
        break;
    case 'productos':
    case 'producto':
        require_once __DIR__ . '/controllers/ProductoController.php';
        break;
    case 'historial':
        include __DIR__ . '/views/historial_ventas.php';
        break;
    case 'login':
    default:
        require_once __DIR__ . '/controllers/LoginController.php';
        break;
}
