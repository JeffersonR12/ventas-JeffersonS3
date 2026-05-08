<?php
/**
 * Controlador: VentaController
 * Gestiona las operaciones de ventas
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Producto.php';

class VentaController {
    private $conexion;
    private $ventaModel;
    private $productoModel;
    
    public function __construct() {
        $this->conexion = conectarBD();
        $this->ventaModel = new Venta($this->conexion);
        $this->productoModel = new Producto($this->conexion);
    }
    
    /**
     * Mostrar página de crear venta
     */
    public function mostrarFormulario() {
        $productos = $this->productoModel->obtenerActivos();
        include __DIR__ . '/../views/venta.php';
    }
    
    /**
     * Procesar crear venta
     */
    public function crearVenta() {
        header('Content-Type: application/json');
        
        try {
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception('Usuario no autenticado');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                throw new Exception('Datos inválidos');
            }
            
            // Validar datos
            $cliente_nombre = isset($data['cliente_nombre']) ? trim($data['cliente_nombre']) : '';
            $cliente_email = isset($data['cliente_email']) ? trim($data['cliente_email']) : '';
            $cliente_telefono = isset($data['cliente_telefono']) ? trim($data['cliente_telefono']) : '';
            $items = isset($data['items']) ? $data['items'] : [];
            
            if (empty($cliente_nombre)) {
                throw new Exception('Nombre del cliente es requerido');
            }
            
            if (empty($items) || !is_array($items)) {
                throw new Exception('Debe agregar al menos un producto');
            }
            
            // Calcular subtotal e IGV
            $subtotal = 0;
            foreach ($items as $item) {
                if (empty($item['producto_id']) || empty($item['cantidad']) || !is_numeric($item['cantidad'])) {
                    throw new Exception('Datos de producto inválidos');
                }
                
                $producto = $this->productoModel->obtenerPorId($item['producto_id']);
                if (!$producto) {
                    throw new Exception('Producto no encontrado: ' . $item['producto_id']);
                }
                
                // Verificar stock
                if (!$this->productoModel->verificarStock($item['producto_id'], $item['cantidad'])) {
                    throw new Exception('Stock insuficiente para: ' . $producto['nombre']);
                }
                
                $item_total = $producto['precio_unitario'] * $item['cantidad'];
                $subtotal += $item_total;
                $item['precio_unitario'] = $producto['precio_unitario'];
            }
            
            $igv = $subtotal * IGV_RATE;
            
            // Crear venta
            $venta_id = $this->ventaModel->crear(
                $_SESSION['usuario_id'],
                $cliente_nombre,
                $cliente_email,
                $cliente_telefono,
                $items,
                $subtotal,
                $igv
            );
            
            echo json_encode([
                'exito' => true,
                'mensaje' => 'Venta creada correctamente',
                'venta_id' => $venta_id,
                'total' => round($subtotal + $igv, 2)
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'exito' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Obtener todas las ventas
     */
    public function obtenerVentasJSON() {
        header('Content-Type: application/json');
        $usuario_id = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : null;
        $limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 50;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        
        $ventas = $this->ventaModel->obtenerTodas($usuario_id, $limite, $offset);
        echo json_encode($ventas);
    }
    
    /**
     * Obtener detalles de venta
     */
    public function obtenerDetallesJSON($venta_id) {
        header('Content-Type: application/json');
        $detalles = $this->ventaModel->obtenerDetalles($venta_id);
        echo json_encode($detalles);
    }
    
    /**
     * Mostrar historial de ventas
     */
    public function mostrarHistorial() {
        $ventas = $this->ventaModel->obtenerTodas();
        include __DIR__ . '/../views/historial_ventas.php';
    }
    
    /**
     * Obtener estadísticas
     */
    public function obtenerEstadisticasJSON() {
        header('Content-Type: application/json');
        $estadisticas = $this->ventaModel->obtenerEstadisticas();
        echo json_encode($estadisticas);
    }
}

// Procesar petición
$controlador = new VentaController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion']) && $_GET['accion'] === 'crear') {
    $controlador->crearVenta();
} elseif (isset($_GET['accion'])) {
    header('Content-Type: application/json');
    
    if ($_GET['accion'] === 'obtener_ventas') {
        $controlador->obtenerVentasJSON();
    } elseif ($_GET['accion'] === 'obtener_detalles' && isset($_GET['venta_id'])) {
        $controlador->obtenerDetallesJSON($_GET['venta_id']);
    } elseif ($_GET['accion'] === 'estadisticas') {
        $controlador->obtenerEstadisticasJSON();
    }
} else {
    $controlador->mostrarFormulario();
}
