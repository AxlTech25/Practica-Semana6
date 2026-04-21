<?php
/**
 * Modelo: Despacho
 * Gestiona información de despachos
 */

class Despacho {
    private $conexion;
    
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    
    /**
     * Obtener todos los despachos activos
     */
    public function obtenerTodos($solo_activos = true) {
        $query = "SELECT * FROM despachos";
        
        if ($solo_activos) {
            $query .= " WHERE estado = 'activo'";
        }
        
        $query .= " ORDER BY nombre ASC";
        $resultado = $this->conexion->query($query);
        
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener despacho por ID
     */
    public function obtenerPorId($id) {
        $query = "SELECT * FROM despachos WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Crear nuevo despacho
     */
    public function crear($nombre, $responsable, $piso, $edificio) {
        $estado = 'activo';
        $query = "INSERT INTO despachos (nombre, responsable, piso, edificio, estado) VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ssis", $nombre, $responsable, $piso, $edificio, $estado);
        
        if ($stmt->execute()) {
            return ['exito' => true, 'id' => $this->conexion->insert_id];
        } else {
            return ['exito' => false, 'mensaje' => $stmt->error];
        }
    }
}
?>
