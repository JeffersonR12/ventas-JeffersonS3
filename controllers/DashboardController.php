<?php
/**
 * Controlador: DashboardController
 * Gestiona el dashboard principal
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Producto.php';

class DashboardController {
    private $conexion;
    private $ventaModel;
    private $productoModel;
    
    public function __construct() {
        $this->conexion = conectarBD();
        $this->ventaModel = new Venta($this->conexion);
        $this->productoModel = new Producto($this->conexion);
    }
    
    /**
     * Mostrar dashboard
     */
    public function mostrar() {
        $ventas_del_dia = $this->ventaModel->obtenerVentasDelDia();
        $estadisticas = $this->ventaModel->obtenerEstadisticas();
        $productos = $this->productoModel->obtenerActivos();
        
        include __DIR__ . '/../views/dashboard.php';
    }
}

// Procesar petición
$controlador = new DashboardController();
$controlador->mostrar();
