<?php
/**
 * Controlador: AutenticacionController
 * Gestiona login, logout y autenticación del sistema
 */

class AutenticacionController {
    private $usuarioModel;
    
    public function __construct($conexion) {
        require_once MODELS_PATH . 'Usuario.php';
        $this->usuarioModel = new Usuario($conexion);
    }
    
    /**
     * Mostrar formulario de login
     */
    public function mostrarLogin() {
        require_once VIEWS_PATH . 'login.php';
    }
    
    /**
     * Procesar login
     */
    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit;
        }

        if (!verificarTokenCSRF($_POST['csrf_token'] ?? '')) {
            $_SESSION['errores'] = ['Solicitud inválida. Por favor, intentelo de nuevo.'];
            header('Location: index.php?pagina=login');
            exit;
        }
        
        $correo = filter_var(trim($_POST['correo'] ?? ''), FILTER_SANITIZE_EMAIL);
        $contraseña = trim($_POST['contraseña'] ?? '');
        
        // Validar campos
        $errores = [];
        if (empty($correo)) {
            $errores[] = 'El correo es requerido';
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo no es válido';
        }
        if (empty($contraseña)) {
            $errores[] = 'La contraseña es requerida';
        }
        
        if (!empty($errores)) {
            setOldInput($_POST);
            $_SESSION['errores'] = $errores;
            header('Location: index.php?pagina=login');
            exit;
        }
        
        // Autenticar
        if ($this->usuarioModel->autenticar($correo, $contraseña)) {
            session_regenerate_id(true);
            clearOldInput();
            $_SESSION['usuario_id'] = $this->usuarioModel->id;
            $_SESSION['usuario_nombre'] = $this->usuarioModel->nombre;
            $_SESSION['usuario_rol'] = $this->usuarioModel->rol;
            $_SESSION['logueado'] = true;
            
            // Registrar log
            $this->registrarLog('LOGIN', 'usuarios', $this->usuarioModel->id, 'Login exitoso');
            
            header('Location: index.php?pagina=dashboard');
            exit;
        } else {
            $_SESSION['errores'] = ['Correo o contraseña incorrectos'];
            $_SESSION['error_login'] = true;
            header('Location: index.php?pagina=login');
            exit;
        }
    }
    
    /**
     * Proceso de logout
     */
    public function logout() {
        if (isset($_SESSION['usuario_id'])) {
            $this->registrarLog('LOGOUT', 'usuarios', $_SESSION['usuario_id'], 'Logout exitoso');
        }

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        header('Location: index.php?pagina=login');
        exit;
    }
    
    /**
     * Registrar log de acceso
     */
    private function registrarLog($accion, $tabla, $registro_id, $detalles) {
        global $conn;
        $usuario_id = $_SESSION['usuario_id'] ?? null;
        
        $query = "INSERT INTO logs_acceso (usuario_id, accion, tabla_afectada, registro_id, detalles) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issss", $usuario_id, $accion, $tabla, $registro_id, $detalles);
        $stmt->execute();
    }
    
    /**
     * Verificar si el usuario está logueado
     */
    public static function verificarSesion() {
        if (!isset($_SESSION['logueado']) || !$_SESSION['logueado']) {
            header('Location: index.php?pagina=login');
            exit;
        }
    }
    
    /**
     * Verificar rol de usuario
     */
    public static function verificarRol($roles_permitidos = []) {
        self::verificarSesion();
        
        if (!empty($roles_permitidos) && !in_array($_SESSION['usuario_rol'], $roles_permitidos)) {
            header('Location: index.php?pagina=dashboard');
            exit;
        }
    }
}
?>
