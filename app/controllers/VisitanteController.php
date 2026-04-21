<?php
/**
 * Controlador: VisitanteController
 * Gestiona el registro, búsqueda y consultas de visitantes
 */

class VisitanteController {
    private $visitanteModel;
    private $despachoModel;
    
    public function __construct($conexion) {
        require_once MODELS_PATH . 'Visitante.php';
        require_once MODELS_PATH . 'Despacho.php';
        
        $this->visitanteModel = new Visitante($conexion);
        $this->despachoModel = new Despacho($conexion);
    }
    
    /**
     * Mostrar formulario de registro
     */
    public function mostrarRegistro() {
        $despachos = $this->despachoModel->obtenerTodos();
        require_once VIEWS_PATH . 'registro_visitante.php';
    }
    
    /**
     * Procesar registro de visitante
     */
    public function procesarRegistro() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?pagina=registro');
            exit;
        }
        
        // Validar datos
        $validacion = $this->validarRegistro($_POST);
        if (!$validacion['valido']) {
            $_SESSION['errores'] = $validacion['errores'];
            header('Location: index.php?pagina=registro');
            exit;
        }
        
        // Preparar datos
        $datos = [
            'nombre_completo' => trim($_POST['nombre_completo']),
            'documento_identidad' => trim($_POST['documento_identidad']),
            'tipo_documento' => $_POST['tipo_documento'],
            'persona_visitada' => trim($_POST['persona_visitada']),
            'despacho_visitado' => (int)$_POST['despacho_visitado'],
            'fecha_visita' => $_POST['fecha_visita'],
            'hora_entrada' => $_POST['hora_entrada'],
            'motivo_visita' => trim($_POST['motivo_visita'] ?? ''),
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'usuario_registro' => $_SESSION['usuario_id']
        ];
        
        // Registrar visitante
        $resultado = $this->visitanteModel->registrar($datos);
        
        if ($resultado['exito']) {
            $_SESSION['mensaje_exito'] = 'Visitante registrado exitosamente (ID: ' . $resultado['id'] . ')';
            header('Location: index.php?pagina=registro');
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
            header('Location: index.php?pagina=registro');
        }
        exit;
    }
    
    /**
     * Mostrar lista de visitantes
     */
    public function mostrarLista() {
        $pagina = $_GET['p'] ?? 1;
        $limite = 20;
        $offset = ($pagina - 1) * $limite;
        
        $visitantes = $this->visitanteModel->obtenerTodos($limite, $offset);
        $total = $this->visitanteModel->obtenerTotal();
        $total_paginas = ceil($total / $limite);
        
        require_once VIEWS_PATH . 'lista_visitantes.php';
    }
    
    /**
     * Mostrar formulario de búsqueda
     */
    public function mostrarBusqueda() {
        $despachos = $this->despachoModel->obtenerTodos();
        $resultados = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $criterios = [
                'documento' => trim($_POST['documento'] ?? ''),
                'fecha' => $_POST['fecha'] ?? '',
                'despacho' => $_POST['despacho'] ?? '',
                'visitante' => trim($_POST['visitante'] ?? '')
            ];
            
            $resultados = $this->visitanteModel->buscar($criterios);
        }
        
        require_once VIEWS_PATH . 'busqueda_visitantes.php';
    }
    
    /**
     * Mostrar detalles de visitante
     */
    public function mostrarDetalles() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id || $id <= 0) {
            $_SESSION['errores'] = ['Visitante no encontrado'];
            header('Location: index.php?pagina=lista');
            exit;
        }
        
        $visitante = $this->visitanteModel->obtenerPorId($id);
        
        if (!$visitante) {
            $_SESSION['errores'] = ['Visitante no encontrado'];
            header('Location: index.php?pagina=lista');
            exit;
        }
        
        require_once VIEWS_PATH . 'detalles_visitante.php';
    }
    
    /**
     * Registrar salida de visitante
     */
    public function registrarSalida() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?pagina=lista');
            exit;
        }
        
        $id = (int)$_POST['id'];
        $hora_salida = trim($_POST['hora_salida']);
        
        if (empty($hora_salida)) {
            $_SESSION['errores'] = ['La hora de salida es requerida'];
            header('Location: index.php?pagina=detalles&id=' . $id);
            exit;
        }
        
        $resultado = $this->visitanteModel->registrarSalida($id, $hora_salida);
        
        if ($resultado['exito']) {
            $_SESSION['mensaje_exito'] = 'Salida registrada. Tiempo de permanencia: ' . $resultado['tiempo_permanencia'];
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
        }
        
        header('Location: index.php?pagina=detalles&id=' . $id);
        exit;
    }
    
    /**
     * Editar visitante
     */
    public function mostrarEdicion() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id || $id <= 0) {
            header('Location: index.php?pagina=lista');
            exit;
        }
        
        $visitante = $this->visitanteModel->obtenerPorId($id);
        $despachos = $this->despachoModel->obtenerTodos();
        
        if (!$visitante) {
            $_SESSION['errores'] = ['Visitante no encontrado'];
            header('Location: index.php?pagina=lista');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre_completo' => trim($_POST['nombre_completo']),
                'documento_identidad' => trim($_POST['documento_identidad']),
                'tipo_documento' => $_POST['tipo_documento'],
                'persona_visitada' => trim($_POST['persona_visitada']),
                'despacho_visitado' => (int)$_POST['despacho_visitado'],
                'fecha_visita' => $_POST['fecha_visita'],
                'hora_entrada' => $_POST['hora_entrada'],
                'motivo_visita' => trim($_POST['motivo_visita'] ?? ''),
                'observaciones' => trim($_POST['observaciones'] ?? '')
            ];
            
            $resultado = $this->visitanteModel->actualizar($id, $datos);
            
            if ($resultado['exito']) {
                $_SESSION['mensaje_exito'] = 'Visitante actualizado';
                header('Location: index.php?pagina=detalles&id=' . $id);
            } else {
                $_SESSION['errores'] = [$resultado['mensaje']];
            }
            exit;
        }
        
        require_once VIEWS_PATH . 'editar_visitante.php';
    }
    
    /**
     * Validar datos de registro
     */
    private function validarRegistro($datos) {
        $errores = [];
        
        if (empty($datos['nombre_completo'])) {
            $errores[] = 'El nombre completo es requerido';
        }
        
        if (empty($datos['documento_identidad'])) {
            $errores[] = 'El documento de identidad es requerido';
        }
        
        if (empty($datos['persona_visitada'])) {
            $errores[] = 'La persona visitada es requerida';
        }
        
        if (empty($datos['despacho_visitado'])) {
            $errores[] = 'El despacho es requerido';
        }
        
        if (empty($datos['fecha_visita'])) {
            $errores[] = 'La fecha es requerida';
        }
        
        if (empty($datos['hora_entrada'])) {
            $errores[] = 'La hora de entrada es requerida';
        }
        
        return [
            'valido' => empty($errores),
            'errores' => $errores
        ];
    }
}
?>
