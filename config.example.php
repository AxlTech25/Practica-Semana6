<?php
/**
 * EJEMPLO DE CONFIGURACIÓN - COPIAR COMO config.php
 * 
 * Este archivo muestra todas las constantes que necesitas
 * para ejecutar el sistema.
 */

// ====================================================================
// CONFIGURACIÓN DE BASE DE DATOS
// ====================================================================

// Host del servidor MySQL
define('DB_HOST', 'localhost');

// Usuario de la base de datos
define('DB_USER', 'root');

// Contraseña de la base de datos
define('DB_PASS', 'root');

// Nombre de la base de datos
define('DB_NAME', 'sistema_visitantes');

// Puerto MySQL (generalmente 3306)
define('DB_PORT', 3306);

// ====================================================================
// CONFIGURACIÓN DE LA APLICACIÓN
// ====================================================================

// URL base de la aplicación
define('BASE_URL', 'http://localhost/Practica-Semana6');

// Rutas de carpetas principales
define('VIEWS_PATH', __DIR__ . '/app/views/');
define('MODELS_PATH', __DIR__ . '/app/models/');
define('CONTROLLERS_PATH', __DIR__ . '/app/controllers/');

// ====================================================================
// CONFIGURACIÓN DE SESIÓN
// ====================================================================

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ====================================================================
// OPCIONES DE DESARROLLO
// ====================================================================

// Descomenta para ver errores en desarrollo
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// ====================================================================
// CONECTAR A LA BASE DE DATOS
// ====================================================================

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    // Verificar si hay error de conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    
    // Configurar charset para caracteres especiales
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("❌ Conexión fallida: " . $e->getMessage());
}

// ====================================================================
// Fin de configuración
// ====================================================================
?>
