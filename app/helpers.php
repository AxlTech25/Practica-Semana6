<?php
/**
 * Helper global para seguridad y valores antiguos en formularios.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Genera o devuelve el token CSRF de sesión.
 */
function generarTokenCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifica que el token CSRF enviado coincida con el de sesión.
 */
function verificarTokenCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

/**
 * Imprime el campo oculto CSRF para los formularios.
 */
function csrfInput() {
    $token = generarTokenCSRF();
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Guarda los datos del formulario en sesión para mantener los valores antiguos.
 */
function setOldInput(array $data) {
    $_SESSION['old_input'] = $data;
}

/**
 * Devuelve el valor anterior de un campo POST para mantener datos al recargar.
 */
function old($name, $default = '') {
    if (isset($_POST[$name])) {
        return htmlspecialchars($_POST[$name], ENT_QUOTES, 'UTF-8');
    }
    if (isset($_SESSION['old_input'][$name])) {
        return htmlspecialchars($_SESSION['old_input'][$name], ENT_QUOTES, 'UTF-8');
    }
    return htmlspecialchars($default, ENT_QUOTES, 'UTF-8');
}

/**
 * Elimina los valores antiguos guardados en sesión.
 */
function clearOldInput() {
    unset($_SESSION['old_input']);
}
