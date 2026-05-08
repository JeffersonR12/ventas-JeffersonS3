<?php
/**
 * Modelo: Venta
 * Gestiona las operaciones de ventas en la base de datos
 */

class Venta {
    private $conexion;
    
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    
    /**
     * Crear nueva venta con detalles
     */
    public function crear($usuario_id, $cliente_nombre, $cliente_email, $cliente_telefono, $items, $subtotal, $igv) {
        $total = $subtotal + $igv;
        
        // Iniciar transacción
        $this->conexion->begin_transaction();
        
        try {
            // Insertar venta
            $query_venta = "INSERT INTO ventas (usuario_id, cliente_nombre, cliente_email, cliente_telefono, subtotal, igv, total) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_venta = $this->conexion->prepare($query_venta);
            $stmt_venta->bind_param("issssdD", $usuario_id, $cliente_nombre, $cliente_email, $cliente_telefono, $subtotal, $igv, $total);
            
            if (!$stmt_venta->execute()) {
                throw new Exception("Error al crear la venta");
            }
            
            $venta_id = $this->conexion->insert_id;
            
            // Insertar detalles de venta
            $query_detalle = "INSERT INTO detalles_venta (venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
            
            foreach ($items as $item) {
                $stmt_detalle = $this->conexion->prepare($query_detalle);
                $subtotal_item = $item['cantidad'] * $item['precio_unitario'];
                $stmt_detalle->bind_param("iiidd", $venta_id, $item['producto_id'], $item['cantidad'], $item['precio_unitario'], $subtotal_item);
                
                if (!$stmt_detalle->execute()) {
                    throw new Exception("Error al crear detalle de venta");
                }
                
                // Actualizar stock del producto
                $this->actualizarStock($item['producto_id'], $item['cantidad']);
            }
            
            // Confirmar transacción
            $this->conexion->commit();
            return $venta_id;
            
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $this->conexion->rollback();
            throw $e;
        }
    }
    
    /**
     * Actualizar stock de producto
     */
    private function actualizarStock($producto_id, $cantidad) {
        $query = "UPDATE productos SET stock = stock - ? WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ii", $cantidad, $producto_id);
        return $stmt->execute();
    }
    
    /**
     * Obtener todas las ventas
     */
    public function obtenerTodas($usuario_id = null, $limite = 50, $offset = 0) {
        if ($usuario_id) {
            $query = "SELECT v.*, u.nombre as vendedor FROM ventas v 
                      LEFT JOIN usuarios u ON v.usuario_id = u.id 
                      WHERE v.usuario_id = ? 
                      ORDER BY v.fecha_venta DESC 
                      LIMIT ? OFFSET ?";
            $stmt = $this->conexion->prepare($query);
            $stmt->bind_param("iii", $usuario_id, $limite, $offset);
        } else {
            $query = "SELECT v.*, u.nombre as vendedor FROM ventas v 
                      LEFT JOIN usuarios u ON v.usuario_id = u.id 
                      ORDER BY v.fecha_venta DESC 
                      LIMIT ? OFFSET ?";
            $stmt = $this->conexion->prepare($query);
            $stmt->bind_param("ii", $limite, $offset);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener venta por ID
     */
    public function obtenerPorId($id) {
        $query = "SELECT v.*, u.nombre as vendedor FROM ventas v 
                  LEFT JOIN usuarios u ON v.usuario_id = u.id 
                  WHERE v.id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Obtener detalles de venta
     */
    public function obtenerDetalles($venta_id) {
        $query = "SELECT dv.*, p.nombre, p.categoria FROM detalles_venta dv 
                  JOIN productos p ON dv.producto_id = p.id 
                  WHERE dv.venta_id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $venta_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener ventas del día
     */
    public function obtenerVentasDelDia() {
        $query = "SELECT v.*, u.nombre as vendedor FROM ventas v 
                  LEFT JOIN usuarios u ON v.usuario_id = u.id 
                  WHERE DATE(v.fecha_venta) = CURDATE() 
                  ORDER BY v.fecha_venta DESC";
        $result = $this->conexion->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener estadísticas de ventas
     */
    public function obtenerEstadisticas() {
        $query = "SELECT 
                    COUNT(*) as total_ventas,
                    SUM(total) as total_ingresos,
                    AVG(total) as promedio_venta
                  FROM ventas 
                  WHERE fecha_venta >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $result = $this->conexion->query($query);
        return $result->fetch_assoc();
    }
    
    /**
     * Actualizar estado de venta
     */
    public function actualizarEstado($id, $estado) {
        $query = "UPDATE ventas SET estado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("si", $estado, $id);
        return $stmt->execute();
    }
    
    /**
     * Eliminar venta
     */
    public function eliminar($id) {
        // Primero eliminar detalles (se eliminarán automáticamente por ON DELETE CASCADE)
        $query = "DELETE FROM ventas WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
