<?php
/**
 * Controlador: ProductoController
 * Gestiona las operaciones de productos
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Producto.php';

class ProductoController {
    private $conexion;
    private $productoModel;
    
    public function __construct() {
        $this->conexion = conectarBD();
        $this->productoModel = new Producto($this->conexion);
    }
    
    /**
     * Obtener todos los productos activos en JSON
     */
    public function obtenerProductosJSON() {
        header('Content-Type: application/json');
        $productos = $this->productoModel->obtenerActivos();
        echo json_encode($productos);
    }
    
    /**
     * Obtener producto por ID en JSON
     */
    public function obtenerProductoJSON($id) {
        header('Content-Type: application/json');
        $producto = $this->productoModel->obtenerPorId($id);
        echo json_encode($producto);
    }
    
    /**
     * Obtener categorías en JSON
     */
    public function obtenerCategoriasJSON() {
        header('Content-Type: application/json');
        $categorias = $this->productoModel->obtenerCategorias();
        echo json_encode($categorias);
    }
    
    /**
     * Obtener productos por categoría en JSON
     */
    public function obtenerPorCategoriaJSON($categoria) {
        header('Content-Type: application/json');
        $productos = $this->productoModel->obtenerPorCategoria($categoria);
        echo json_encode($productos);
    }
    
    /**
     * Mostrar página de productos
     */
    public function mostrarProductos() {
        $productos = $this->productoModel->obtenerActivos();
        include __DIR__ . '/../views/productos.php';
    }
}

// Procesar petición
$controlador = new ProductoController();

if (isset($_GET['accion'])) {
    header('Content-Type: application/json');
    
    if ($_GET['accion'] === 'obtener_productos') {
        $controlador->obtenerProductosJSON();
    } elseif ($_GET['accion'] === 'obtener_categorias') {
        $controlador->obtenerCategoriasJSON();
    } elseif ($_GET['accion'] === 'obtener_por_categoria' && isset($_GET['categoria'])) {
        $controlador->obtenerPorCategoriaJSON($_GET['categoria']);
    } elseif ($_GET['accion'] === 'obtener_producto' && isset($_GET['id'])) {
        $controlador->obtenerProductoJSON($_GET['id']);
    }
} else {
    $controlador->mostrarProductos();
}
