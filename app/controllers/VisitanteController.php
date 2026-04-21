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
     * Verificar token CSRF en formularios
     */
    private function verificarCSRF($pagina = 'registro') {
        if (!verificarTokenCSRF($_POST['csrf_token'] ?? '')) {
            $_SESSION['errores'] = ['Token de seguridad inválido. Vuelva a cargar la página.'];
            header('Location: index.php?pagina=' . $pagina);
            exit;
        }
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

        $this->verificarCSRF('registro');
        
        // Validar datos
        $validacion = $this->validarRegistro($_POST);
        if (!$validacion['valido']) {
            setOldInput($_POST);
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
            clearOldInput();
            $_SESSION['mensaje_exito'] = 'Visitante registrado exitosamente (ID: ' . $resultado['id'] . ')';
            header('Location: index.php?pagina=registro');
        } else {
            setOldInput($_POST);
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
            $this->verificarCSRF('busqueda');

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

        $this->verificarCSRF('detalles');
        
        $id = (int)$_POST['id'];
        $hora_salida = trim($_POST['hora_salida']);
        
        if (empty($hora_salida)) {
            $_SESSION['errores'] = ['La hora de salida es requerida'];
            header('Location: index.php?pagina=detalles&id=' . $id);
            exit;
        }

        $hora = DateTime::createFromFormat('H:i', $hora_salida);
        if (!$hora) {
            $_SESSION['errores'] = ['Formato de hora inválido'];
            header('Location: index.php?pagina=detalles&id=' . $id);
            exit;
        }
        
        $resultado = $this->visitanteModel->registrarSalida($id, $hora->format('H:i:s'));
        
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
            $this->verificarCSRF('editar');

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

            $validacion = $this->validarRegistro($datos);
            if (!$validacion['valido']) {
                setOldInput($_POST);
                $_SESSION['errores'] = $validacion['errores'];
                header('Location: index.php?pagina=editar&id=' . $id);
                exit;
            }
            
            $resultado = $this->visitanteModel->actualizar($id, $datos);
            
            if ($resultado['exito']) {
                clearOldInput();
                $_SESSION['mensaje_exito'] = 'Visitante actualizado';
                header('Location: index.php?pagina=detalles&id=' . $id);
            } else {
                setOldInput($_POST);
                $_SESSION['errores'] = [$resultado['mensaje']];
                header('Location: index.php?pagina=editar&id=' . $id);
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
        } elseif (mb_strlen($datos['nombre_completo']) > 100) {
            $errores[] = 'El nombre completo no puede tener más de 100 caracteres';
        }

        if (empty($datos['documento_identidad'])) {
            $errores[] = 'El documento de identidad es requerido';
        } elseif (mb_strlen($datos['documento_identidad']) > 20) {
            $errores[] = 'El documento de identidad no puede tener más de 20 caracteres';
        }

        if (empty($datos['persona_visitada'])) {
            $errores[] = 'La persona visitada es requerida';
        } elseif (mb_strlen($datos['persona_visitada']) > 100) {
            $errores[] = 'La persona visitada no puede tener más de 100 caracteres';
        }

        if (empty($datos['despacho_visitado']) || !is_numeric($datos['despacho_visitado']) || $datos['despacho_visitado'] <= 0) {
            $errores[] = 'El despacho es requerido';
        }

        if (empty($datos['fecha_visita'])) {
            $errores[] = 'La fecha es requerida';
        } else {
            $fecha = DateTime::createFromFormat('Y-m-d', $datos['fecha_visita']);
            if (!$fecha || $fecha->format('Y-m-d') !== $datos['fecha_visita']) {
                $errores[] = 'La fecha no tiene un formato válido';
            }
        }

        if (empty($datos['hora_entrada'])) {
            $errores[] = 'La hora de entrada es requerida';
        } else {
            $hora = DateTime::createFromFormat('H:i', $datos['hora_entrada']);
            if (!$hora) {
                $errores[] = 'La hora de entrada no tiene un formato válido';
            }
        }

        if (!empty($datos['motivo_visita']) && mb_strlen($datos['motivo_visita']) > 255) {
            $errores[] = 'El motivo de la visita no puede tener más de 255 caracteres';
        }

        if (!empty($datos['observaciones']) && mb_strlen($datos['observaciones']) > 255) {
            $errores[] = 'Las observaciones no pueden tener más de 255 caracteres';
        }

        if (!empty($datos['tipo_documento']) && !in_array($datos['tipo_documento'], ['cedula', 'pasaporte', 'otro'], true)) {
            $errores[] = 'El tipo de documento no es válido';
        }

        return [
            'valido' => empty($errores),
            'errores' => $errores
        ];
    }
}
?>
