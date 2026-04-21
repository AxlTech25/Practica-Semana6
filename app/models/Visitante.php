<?php
/**
 * Modelo: Visitante
 * Gestiona el registro, búsqueda y consultas de visitantes
 */

class Visitante {
    private $conexion;
    
    public $id;
    public $nombre_completo;
    public $documento_identidad;
    public $persona_visitada;
    public $despacho_visitado;
    public $fecha_visita;
    public $hora_entrada;
    public $hora_salida;
    public $tiempo_permanencia;
    
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    
    /**
     * Registrar un nuevo visitante
     */
    public function registrar($datos) {
        $query = "INSERT INTO visitantes (
            nombre_completo, 
            documento_identidad, 
            tipo_documento,
            persona_visitada, 
            despacho_visitado, 
            fecha_visita, 
            hora_entrada,
            motivo_visita,
            observaciones,
            usuario_registro
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param(
            "ssssiisssi",
            $datos['nombre_completo'],
            $datos['documento_identidad'],
            $datos['tipo_documento'],
            $datos['persona_visitada'],
            $datos['despacho_visitado'],
            $datos['fecha_visita'],
            $datos['hora_entrada'],
            $datos['motivo_visita'],
            $datos['observaciones'],
            $datos['usuario_registro']
        );
        
        if ($stmt->execute()) {
            return ['exito' => true, 'id' => $this->conexion->insert_id, 'mensaje' => 'Visitante registrado'];
        } else {
            return ['exito' => false, 'mensaje' => 'Error al registrar: ' . $stmt->error];
        }
    }
    
    /**
     * Registrar hora de salida y calcular tiempo de permanencia
     */
    public function registrarSalida($id, $hora_salida) {
        // Obtener hora de entrada
        $query_entrada = "SELECT hora_entrada, fecha_visita FROM visitantes WHERE id = ?";
        $stmt_entrada = $this->conexion->prepare($query_entrada);
        $stmt_entrada->bind_param("i", $id);
        $stmt_entrada->execute();
        $resultado = $stmt_entrada->get_result()->fetch_assoc();
        
        if (!$resultado) {
            return ['exito' => false, 'mensaje' => 'Visitante no encontrado'];
        }
        
        // Calcular tiempo de permanencia
        $tiempo_permanencia = $this->calcularTiempoPermancia(
            $resultado['hora_entrada'], 
            $hora_salida
        );
        
        // Actualizar hora de salida y tiempo de permanencia
        $estado = 'finalizada';
        $query = "UPDATE visitantes SET hora_salida = ?, tiempo_permanencia = ?, estado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("sssi", $hora_salida, $tiempo_permanencia, $estado, $id);
        
        if ($stmt->execute()) {
            return [
                'exito' => true, 
                'tiempo_permanencia' => $tiempo_permanencia,
                'mensaje' => 'Salida registrada correctamente'
            ];
        } else {
            return ['exito' => false, 'mensaje' => 'Error al registrar salida'];
        }
    }
    
    /**
     * Calcular tiempo de permanencia
     */
    private function calcularTiempoPermancia($hora_entrada, $hora_salida) {
        $entrada = DateTime::createFromFormat('H:i:s', $hora_entrada);
        $salida = DateTime::createFromFormat('H:i:s', $hora_salida);
        
        if (!$entrada || !$salida) {
            return "Error en formato";
        }
        
        $diferencia = $salida->diff($entrada);
        
        $horas = $diferencia->h;
        $minutos = $diferencia->i;
        $segundos = $diferencia->s;
        
        $resultado = "";
        if ($horas > 0) {
            $resultado .= $horas . " hora" . ($horas > 1 ? "s" : "");
        }
        if ($minutos > 0) {
            if ($resultado) $resultado .= " ";
            $resultado .= $minutos . " minuto" . ($minutos > 1 ? "s" : "");
        }
        if (!$resultado) {
            $resultado = "Menos de 1 minuto";
        }
        
        return $resultado;
    }
    
    /**
     * Obtener visitante por ID
     */
    public function obtenerPorId($id) {
        $query = "SELECT v.*, d.nombre as despacho_nombre FROM visitantes v 
                  LEFT JOIN despachos d ON v.despacho_visitado = d.id 
                  WHERE v.id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Buscar visitantes por criterios
     */
    public function buscar($criterios = []) {
        $query = "SELECT v.*, d.nombre as despacho_nombre FROM visitantes v 
                  LEFT JOIN despachos d ON v.despacho_visitado = d.id 
                  WHERE 1=1";
        
        $params = [];
        $tipos = "";
        
        // Búsqueda por documento
        if (!empty($criterios['documento'])) {
            $query .= " AND v.documento_identidad LIKE ?";
            $documento = "%" . $criterios['documento'] . "%";
            $params[] = $documento;
            $tipos .= "s";
        }
        
        // Búsqueda por fecha
        if (!empty($criterios['fecha'])) {
            $query .= " AND v.fecha_visita = ?";
            $params[] = $criterios['fecha'];
            $tipos .= "s";
        }
        
        // Búsqueda por despacho
        if (!empty($criterios['despacho'])) {
            $query .= " AND v.despacho_visitado = ?";
            $params[] = $criterios['despacho'];
            $tipos .= "i";
        }
        
        // Búsqueda por visitante
        if (!empty($criterios['visitante'])) {
            $query .= " AND v.nombre_completo LIKE ?";
            $visitante = "%" . $criterios['visitante'] . "%";
            $params[] = $visitante;
            $tipos .= "s";
        }
        
        // Búsqueda por estado
        if (!empty($criterios['estado'])) {
            $query .= " AND v.estado = ?";
            $params[] = $criterios['estado'];
            $tipos .= "s";
        }
        
        $query .= " ORDER BY v.fecha_visita DESC, v.hora_entrada DESC";
        
        if (!empty($params)) {
            $stmt = $this->conexion->prepare($query);
            $stmt->bind_param($tipos, ...$params);
            $stmt->execute();
            $resultado = $stmt->get_result();
        } else {
            $resultado = $this->conexion->query($query);
        }
        
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener todos los visitantes
     */
    public function obtenerTodos($limite = 100, $offset = 0) {
        $query = "SELECT v.*, d.nombre as despacho_nombre FROM visitantes v 
                  LEFT JOIN despachos d ON v.despacho_visitado = d.id 
                  ORDER BY v.fecha_visita DESC, v.hora_entrada DESC
                  LIMIT ? OFFSET ?";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ii", $limite, $offset);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener total de visitantes
     */
    public function obtenerTotal() {
        $query = "SELECT COUNT(*) as total FROM visitantes";
        $resultado = $this->conexion->query($query);
        $row = $resultado->fetch_assoc();
        
        return $row['total'];
    }
    
    /**
     * Actualizar visitante
     */
    public function actualizar($id, $datos) {
        $query = "UPDATE visitantes SET 
                  nombre_completo = ?,
                  documento_identidad = ?,
                  tipo_documento = ?,
                  persona_visitada = ?,
                  despacho_visitado = ?,
                  fecha_visita = ?,
                  hora_entrada = ?,
                  motivo_visita = ?,
                  observaciones = ?
                  WHERE id = ?";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param(
            "ssssiisssi",
            $datos['nombre_completo'],
            $datos['documento_identidad'],
            $datos['tipo_documento'],
            $datos['persona_visitada'],
            $datos['despacho_visitado'],
            $datos['fecha_visita'],
            $datos['hora_entrada'],
            $datos['motivo_visita'],
            $datos['observaciones'],
            $id
        );
        
        if ($stmt->execute()) {
            return ['exito' => true, 'mensaje' => 'Visitante actualizado'];
        } else {
            return ['exito' => false, 'mensaje' => 'Error al actualizar'];
        }
    }
    
    /**
     * Eliminar/anular visitante
     */
    public function eliminar($id) {
        $estado = 'anulada';
        $query = "UPDATE visitantes SET estado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("si", $estado, $id);
        
        if ($stmt->execute()) {
            return ['exito' => true, 'mensaje' => 'Visitante anulado'];
        } else {
            return ['exito' => false, 'mensaje' => 'Error al anular'];
        }
    }
}
?>
