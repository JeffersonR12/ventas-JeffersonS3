<?php
/**
 * Controlador: LoginController
 * Gestiona el login y logout de usuarios
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Usuario.php';

class LoginController {
    private $conexion;
    private $usuarioModel;
    
    public function __construct() {
        $this->conexion = conectarBD();
        $this->usuarioModel = new Usuario($this->conexion);
    }
    
    /**
     * Mostrar formulario de login
     */
    public function mostrarFormulario() {
        include __DIR__ . '/../views/login.php';
    }
    
    /**
     * Procesar login
     */
    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $contraseña = isset($_POST['contraseña']) ? $_POST['contraseña'] : '';
            
            if (empty($email) || empty($contraseña)) {
                $_SESSION['error'] = 'Email y contraseña son requeridos';
                return false;
            }
            
            $usuario = $this->usuarioModel->validarCredenciales($email, $contraseña);
            
            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                $_SESSION['usuario_email'] = $usuario['email'];
                return true;
            } else {
                $_SESSION['error'] = 'Email o contraseña inválidos';
                return false;
            }
        }
        return false;
    }
    
    /**
     * Procesar logout
     */
    public function logout() {
        session_destroy();
        header('Location: index.php?pagina=login');
        exit;
    }
}

// Procesar petición
$controlador = new LoginController();

if (isset($_GET['accion'])) {
    if ($_GET['accion'] === 'logout') {
        $controlador->logout();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($controlador->procesar()) {
        header('Location: index.php?pagina=dashboard');
        exit;
    } else {
        header('Location: index.php?pagina=login');
        exit;
    }
}

$controlador->mostrarFormulario();
