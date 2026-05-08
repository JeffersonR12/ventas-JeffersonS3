<?php
/**
 * Modelo: Usuario
 * Gestiona las operaciones de usuarios en la base de datos
 */

class Usuario {
    private $conexion;
    
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    
    /**
     * Obtener usuario por email
     */
    public function obtenerPorEmail($email) {
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Crear nuevo usuario
     */
    public function crear($nombre, $email, $contraseña, $rol = 'vendedor') {
        $contraseña_hash = hash('sha256', $contraseña);
        $query = "INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ssss", $nombre, $email, $contraseña_hash, $rol);
        return $stmt->execute();
    }
    
    /**
     * Validar credenciales
     */
    public function validarCredenciales($email, $contraseña) {
        $usuario = $this->obtenerPorEmail($email);
        if ($usuario && $usuario['estado'] === 'activo') {
            $contraseña_hash = hash('sha256', $contraseña);
            if ($usuario['contraseña'] === $contraseña_hash) {
                return $usuario;
            }
        }
        return false;
    }
    
    /**
     * Obtener todos los usuarios
     */
    public function obtenerTodos() {
        $query = "SELECT id, nombre, email, rol, estado, fecha_creacion FROM usuarios ORDER BY fecha_creacion DESC";
        $result = $this->conexion->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener usuario por ID
     */
    public function obtenerPorId($id) {
        $query = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Actualizar usuario
     */
    public function actualizar($id, $nombre, $email, $rol, $estado) {
        $query = "UPDATE usuarios SET nombre = ?, email = ?, rol = ?, estado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ssssi", $nombre, $email, $rol, $estado, $id);
        return $stmt->execute();
    }
    
    /**
     * Eliminar usuario
     */
    public function eliminar($id) {
        $query = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
