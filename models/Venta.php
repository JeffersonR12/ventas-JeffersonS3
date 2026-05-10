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

    public function crear($usuario_id, $cliente, $email, $tel, $items, $subtotal, $igv) {
        $this->conexion->begin_transaction();
        try {
            $total = $subtotal + $igv;
            $query = "INSERT INTO ventas (usuario_id, cliente_nombre, cliente_email, cliente_telefono, subtotal, igv, total) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($query);
            $stmt->bind_param("isssddd", $usuario_id, $cliente, $email, $tel, $subtotal, $igv, $total);
            $stmt->execute();
            $venta_id = $this->conexion->insert_id;

            foreach ($items as $item) {
                $qDetalle = "INSERT INTO detalles_venta (venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
                $stDetalle = $this->conexion->prepare($qDetalle);
                $detalleSubtotal = isset($item['subtotal']) ? $item['subtotal'] : ($item['precio_unitario'] * $item['cantidad']);
                $detallePrecio = isset($item['precio_unitario']) ? $item['precio_unitario'] : 0;
                $stDetalle->bind_param("iiidd", $venta_id, $item['producto_id'], $item['cantidad'], $detallePrecio, $detalleSubtotal);
                $stDetalle->execute();
            }

            $this->conexion->commit();
            return $venta_id;
        } catch (Exception $e) {
            $this->conexion->rollback();
            throw $e;
        }
    }

    public function obtenerVentasDelDia() {
        $query = "SELECT COUNT(*) as total FROM ventas WHERE DATE(fecha_venta) = CURDATE()";
        return $this->conexion->query($query)->fetch_assoc();
    }

    public function obtenerEstadisticas() {
        $query = "SELECT SUM(total) as ingresos_hoy FROM ventas WHERE DATE(fecha_venta) = CURDATE()";
        return $this->conexion->query($query)->fetch_assoc();
    }

    public function obtenerTodas($usuario_id = null, $limite = null, $offset = 0) {
        $query = "SELECT * FROM ventas WHERE 1=1";
        $params = [];
        $types = "";
        
        if ($usuario_id) {
            $query .= " AND usuario_id = ?";
            $params[] = $usuario_id;
            $types .= "i";
        }
        
        $query .= " ORDER BY fecha_venta DESC";
        
        if ($limite) {
            $query .= " LIMIT ?";
            $params[] = $limite;
            $types .= "i";
            
            if ($offset > 0) {
                $query .= " OFFSET ?";
                $params[] = $offset;
                $types .= "i";
            }
        }
        
        if (empty($params)) {
            return $this->conexion->query($query)->fetch_all(MYSQLI_ASSOC);
        } else {
            $stmt = $this->conexion->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function obtenerDetalles($venta_id) {
        $query = "SELECT v.*, dv.*, p.nombre as producto_nombre 
                  FROM ventas v 
                  JOIN detalles_venta dv ON v.id = dv.venta_id 
                  JOIN productos p ON dv.producto_id = p.id 
                  WHERE v.id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $venta_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}