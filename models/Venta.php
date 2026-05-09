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
                $stDetalle->bind_param("iiidd", $venta_id, $item['producto_id'], $item['cantidad'], $item['precio_unitario'], $item['subtotal']);
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

    public function obtenerTodas() {
        return $this->conexion->query("SELECT * FROM ventas ORDER BY fecha_venta DESC")->fetch_all(MYSQLI_ASSOC);
    }
}