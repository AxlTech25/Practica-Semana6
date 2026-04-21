<?php
/**
 * Modelo: Reporte
 * Genera reportes y estadísticas del sistema
 */

class Reporte {
    private $conexion;
    
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    
    /**
     * Obtener visitas por día
     */
    public function visitasPorDia($fecha_inicio = null, $fecha_fin = null) {
        if (!$fecha_inicio) {
            $fecha_inicio = date('Y-m-d', strtotime('-30 days'));
        }
        if (!$fecha_fin) {
            $fecha_fin = date('Y-m-d');
        }
        
        $query = "SELECT 
                    fecha_visita,
                    COUNT(*) as total_visitas,
                    COUNT(CASE WHEN estado = 'finalizada' THEN 1 END) as visitas_finalizadas
                  FROM visitantes
                  WHERE fecha_visita BETWEEN ? AND ? AND estado != 'anulada'
                  GROUP BY fecha_visita
                  ORDER BY fecha_visita DESC";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener visitas por despacho
     */
    public function visitasPorDespacho($fecha_inicio = null, $fecha_fin = null) {
        if (!$fecha_inicio) {
            $fecha_inicio = date('Y-m-d', strtotime('-30 days'));
        }
        if (!$fecha_fin) {
            $fecha_fin = date('Y-m-d');
        }
        
        $query = "SELECT 
                    d.id,
                    d.nombre,
                    d.responsable,
                    COUNT(v.id) as total_visitas
                  FROM despachos d
                  LEFT JOIN visitantes v ON d.id = v.despacho_visitado 
                    AND v.fecha_visita BETWEEN ? AND ? 
                    AND v.estado != 'anulada'
                  WHERE d.estado = 'activo'
                  GROUP BY d.id, d.nombre, d.responsable
                  ORDER BY total_visitas DESC";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener tiempo promedio de permanencia
     */
    public function tiempoPromedioPermanencia($fecha_inicio = null, $fecha_fin = null, $despacho_id = null) {
        if (!$fecha_inicio) {
            $fecha_inicio = date('Y-m-d', strtotime('-30 days'));
        }
        if (!$fecha_fin) {
            $fecha_fin = date('Y-m-d');
        }
        
        $query = "SELECT 
                    COUNT(*) as total_visitas,
                    AVG(TIME_TO_SEC(TIMEDIFF(hora_salida, hora_entrada))/60) as minutos_promedio
                  FROM visitantes
                  WHERE estado = 'finalizada'
                    AND hora_salida IS NOT NULL
                    AND fecha_visita BETWEEN ? AND ?";
        
        $params = [$fecha_inicio, $fecha_fin];
        $tipos = "ss";
        
        if ($despacho_id) {
            $query .= " AND despacho_visitado = ?";
            $params[] = $despacho_id;
            $tipos .= "i";
        }
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param($tipos, ...$params);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Obtener historial de visitas detallado
     */
    public function historialVisitas($fecha_inicio = null, $fecha_fin = null, $despacho_id = null) {
        if (!$fecha_inicio) {
            $fecha_inicio = date('Y-m-d', strtotime('-90 days'));
        }
        if (!$fecha_fin) {
            $fecha_fin = date('Y-m-d');
        }
        
        $query = "SELECT 
                    v.id,
                    v.nombre_completo,
                    v.documento_identidad,
                    v.tipo_documento,
                    v.persona_visitada,
                    d.nombre as despacho_nombre,
                    v.fecha_visita,
                    v.hora_entrada,
                    v.hora_salida,
                    v.tiempo_permanencia,
                    v.motivo_visita,
                    v.estado,
                    u.nombre as usuario_registro
                  FROM visitantes v
                  LEFT JOIN despachos d ON v.despacho_visitado = d.id
                  LEFT JOIN usuarios u ON v.usuario_registro = u.id
                  WHERE v.fecha_visita BETWEEN ? AND ? AND v.estado != 'anulada'";
        
        $params = [$fecha_inicio, $fecha_fin];
        $tipos = "ss";
        
        if ($despacho_id) {
            $query .= " AND v.despacho_visitado = ?";
            $params[] = $despacho_id;
            $tipos .= "i";
        }
        
        $query .= " ORDER BY v.fecha_visita DESC, v.hora_entrada DESC";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param($tipos, ...$params);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener estadísticas generales
     */
    public function estadisticasGenerales($fecha_inicio = null, $fecha_fin = null) {
        if (!$fecha_inicio) {
            $fecha_inicio = date('Y-m-d', strtotime('-30 days'));
        }
        if (!$fecha_fin) {
            $fecha_fin = date('Y-m-d');
        }
        
        $query = "SELECT 
                    COUNT(*) as total_visitas,
                    COUNT(CASE WHEN estado = 'activa' THEN 1 END) as visitas_activas,
                    COUNT(CASE WHEN estado = 'finalizada' THEN 1 END) as visitas_finalizadas,
                    COUNT(DISTINCT documento_identidad) as visitantes_unicos,
                    COUNT(CASE WHEN hora_salida IS NOT NULL THEN 1 END) as con_salida_registrada
                  FROM visitantes
                  WHERE fecha_visita BETWEEN ? AND ? AND estado != 'anulada'";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Obtener hora pico de visitas
     */
    public function horasPicoVisitas($fecha_inicio = null, $fecha_fin = null) {
        if (!$fecha_inicio) {
            $fecha_inicio = date('Y-m-d', strtotime('-30 days'));
        }
        if (!$fecha_fin) {
            $fecha_fin = date('Y-m-d');
        }
        
        $query = "SELECT 
                    HOUR(hora_entrada) as hora,
                    COUNT(*) as total_entradas
                  FROM visitantes
                  WHERE fecha_visita BETWEEN ? AND ? AND estado != 'anulada'
                  GROUP BY HOUR(hora_entrada)
                  ORDER BY total_entradas DESC
                  LIMIT 5";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
