<?php
/**
 * ====================================================================
 * ARCHIVO PRINCIPAL - SISTEMA DE REGISTRO DE VISITANTES
 * ====================================================================
 * 
 * Este archivo es el punto de entrada para toda la aplicación MVC.
 * Controla:
 * - Carga de configuración
 * - Enrutamiento de solicitudes
 * - Carga de controladores y vistas
 * - Validación de sesiones
 * 
 * ====================================================================
 */

// Incluir configuración de la aplicación
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/app/helpers.php';

// Determinar qué página/acción se solicita
$pagina = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['pagina'] ?? 'login');
$accion = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['accion'] ?? '');

// Verificar si el usuario está logueado
$logueado = isset($_SESSION['logueado']) && $_SESSION['logueado'];

// ====================================================================
// GESTIÓN DE AUTENTICACIÓN
// ====================================================================

// Si no está logueado, solo permitir login
if (!$logueado && $pagina !== 'login' && !$accion) {
    $pagina = 'login';
}

// Acciones de autenticación
if ($accion === 'procesarLogin') {
    require_once CONTROLLERS_PATH . 'AutenticacionController.php';
    $authController = new AutenticacionController($conn);
    $authController->procesarLogin();
    exit;
}

if ($accion === 'logout') {
    require_once CONTROLLERS_PATH . 'AutenticacionController.php';
    $authController = new AutenticacionController($conn);
    $authController->logout();
    exit;
}

// ====================================================================
// ENRUTADOR PRINCIPAL
// ====================================================================

switch ($pagina) {
    
    // ---- PÁGINAS DE AUTENTICACIÓN ----
    case 'login':
        require_once CONTROLLERS_PATH . 'AutenticacionController.php';
        $authController = new AutenticacionController($conn);
        $authController->mostrarLogin();
        break;
    
    // ---- PÁGINAS DE VISITANTES ----
    case 'dashboard':
        require_once CONTROLLERS_PATH . 'AutenticacionController.php';
        AutenticacionController::verificarSesion();
        require_once VIEWS_PATH . 'dashboard.php';
        break;
    
    case 'registro':
        require_once CONTROLLERS_PATH . 'AutenticacionController.php';
        AutenticacionController::verificarSesion();
        require_once CONTROLLERS_PATH . 'VisitanteController.php';
        $visitanteController = new VisitanteController($conn);
        $visitanteController->mostrarRegistro();
        break;
    
    case 'lista':
        require_once CONTROLLERS_PATH . 'AutenticacionController.php';
        AutenticacionController::verificarSesion();
        require_once CONTROLLERS_PATH . 'VisitanteController.php';
        $visitanteController = new VisitanteController($conn);
        $visitanteController->mostrarLista();
        break;
    
    case 'busqueda':
        require_once CONTROLLERS_PATH . 'AutenticacionController.php';
        AutenticacionController::verificarSesion();
        require_once CONTROLLERS_PATH . 'VisitanteController.php';
        $visitanteController = new VisitanteController($conn);
        $visitanteController->mostrarBusqueda();
        break;
    
    case 'detalles':
        require_once CONTROLLERS_PATH . 'AutenticacionController.php';
        AutenticacionController::verificarSesion();
        require_once CONTROLLERS_PATH . 'VisitanteController.php';
        $visitanteController = new VisitanteController($conn);
        $visitanteController->mostrarDetalles();
        break;
    
    case 'editar':
        require_once CONTROLLERS_PATH . 'AutenticacionController.php';
        AutenticacionController::verificarSesion();
        require_once CONTROLLERS_PATH . 'VisitanteController.php';
        $visitanteController = new VisitanteController($conn);
        $visitanteController->mostrarEdicion();
        break;
    
    // ---- ACCIONES DE VISITANTES ----
    case 'procesarRegistro':
    case 'registrarSalida':
        if ($accion) {
            require_once CONTROLLERS_PATH . 'AutenticacionController.php';
            AutenticacionController::verificarSesion();
            require_once CONTROLLERS_PATH . 'VisitanteController.php';
            $visitanteController = new VisitanteController($conn);
            
            if ($_POST['accion'] === 'registrarSalida') {
                $visitanteController->registrarSalida();
            }
        }
        break;
    
    // ---- PÁGINAS DE REPORTES ----
    case 'reportes':
        require_once CONTROLLERS_PATH . 'AutenticacionController.php';
        AutenticacionController::verificarSesion();
        AutenticacionController::verificarRol(['admin', 'seguridad']);
        require_once CONTROLLERS_PATH . 'ReporteController.php';
        $reporteController = new ReporteController($conn);
        $reporteController->mostrarDashboard();
        break;
    
    case 'historial':
        require_once CONTROLLERS_PATH . 'AutenticacionController.php';
        AutenticacionController::verificarSesion();
        AutenticacionController::verificarRol(['admin', 'seguridad']);
        require_once CONTROLLERS_PATH . 'ReporteController.php';
        $reporteController = new ReporteController($conn);
        $reporteController->mostrarHistorial();
        break;
    
    // ---- EXPORTACIÓN DE DATOS ----
    case 'exportarCSV':
        require_once CONTROLLERS_PATH . 'AutenticacionController.php';
        AutenticacionController::verificarSesion();
        AutenticacionController::verificarRol(['admin', 'seguridad']);
        require_once CONTROLLERS_PATH . 'ReporteController.php';
        $reporteController = new ReporteController($conn);
        $reporteController->exportarCSV();
        exit;
    
    case 'exportarExcel':
        require_once CONTROLLERS_PATH . 'AutenticacionController.php';
        AutenticacionController::verificarSesion();
        AutenticacionController::verificarRol(['admin', 'seguridad']);
        require_once CONTROLLERS_PATH . 'ReporteController.php';
        $reporteController = new ReporteController($conn);
        $reporteController->exportarExcel();
        exit;
    
    // ---- MANEJO DE ACCIONES (POST) ----
    default:
        // Si llega una acción POST, procesarla
        if ($accion === 'procesarRegistro') {
            require_once CONTROLLERS_PATH . 'AutenticacionController.php';
            AutenticacionController::verificarSesion();
            require_once CONTROLLERS_PATH . 'VisitanteController.php';
            $visitanteController = new VisitanteController($conn);
            $visitanteController->procesarRegistro();
            exit;
        }
        elseif ($accion === 'registrarSalida') {
            require_once CONTROLLERS_PATH . 'AutenticacionController.php';
            AutenticacionController::verificarSesion();
            require_once CONTROLLERS_PATH . 'VisitanteController.php';
            $visitanteController = new VisitanteController($conn);
            $visitanteController->registrarSalida();
            exit;
        }
        else {
            // Si no encuentra la página, redirigir al dashboard
            header('Location: index.php?pagina=dashboard');
            exit;
        }
}

// Cerrar conexión (opcional, se cerrará automáticamente al terminar)
// $conn->close();
?>
