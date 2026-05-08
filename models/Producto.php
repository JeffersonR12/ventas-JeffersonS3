<?php
/**
 * Modelo: Producto
 * Gestiona las operaciones de productos en la base de datos
 */

class Producto {
    private $conexion;
    
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    
    /**
     * Obtener todos los productos activos
     */
    public function obtenerActivos() {
        $query = "SELECT id, nombre, descripcion, categoria, precio_unitario, stock FROM productos WHERE estado = 'activo' ORDER BY categoria, nombre";
        $result = $this->conexion->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener productos por categoría
     */
    public function obtenerPorCategoria($categoria) {
        $query = "SELECT id, nombre, descripcion, categoria, precio_unitario, stock FROM productos WHERE estado = 'activo' AND categoria = ? ORDER BY nombre";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $categoria);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener producto por ID
     */
    public function obtenerPorId($id) {
        $query = "SELECT * FROM productos WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Obtener todas las categorías
     */
    public function obtenerCategorias() {
        $query = "SELECT DISTINCT categoria FROM productos WHERE estado = 'activo' ORDER BY categoria";
        $result = $this->conexion->query($query);
        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row['categoria'];
        }
        return $categorias;
    }
    
    /**
     * Crear nuevo producto
     */
    public function crear($nombre, $descripcion, $categoria, $precio_unitario, $stock) {
        $query = "INSERT INTO productos (nombre, descripcion, categoria, precio_unitario, stock) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("sssdi", $nombre, $descripcion, $categoria, $precio_unitario, $stock);
        return $stmt->execute();
    }
    
    /**
     * Actualizar producto
     */
    public function actualizar($id, $nombre, $descripcion, $categoria, $precio_unitario, $stock, $estado) {
        $query = "UPDATE productos SET nombre = ?, descripcion = ?, categoria = ?, precio_unitario = ?, stock = ?, estado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("sssdisi", $nombre, $descripcion, $categoria, $precio_unitario, $stock, $estado, $id);
        return $stmt->execute();
    }
    
    /**
     * Actualizar stock
     */
    public function actualizarStock($id, $cantidad) {
        $query = "UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("iii", $cantidad, $id, $cantidad);
        return $stmt->execute();
    }
    
    /**
     * Verificar disponibilidad de stock
     */
    public function verificarStock($id, $cantidad) {
        $query = "SELECT stock FROM productos WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado && $resultado['stock'] >= $cantidad;
    }
    
    /**
     * Obtener todos los productos (incluye inactivos)
     */
    public function obtenerTodos() {
        $query = "SELECT * FROM productos ORDER BY categoria, nombre";
        $result = $this->conexion->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Eliminar producto
     */
    public function eliminar($id) {
        $query = "DELETE FROM productos WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
