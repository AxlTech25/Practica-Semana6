<?php
/**
 * Modelo: Usuario
 * Gestiona autenticación y usuarios del sistema
 */

class Usuario {
    private $conexion;
    
    public $id;
    public $nombre;
    public $correo;
    public $rol;
    public $estado;
    
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    
    /**
     * Autenticar usuario
     */
    public function autenticar($correo, $contraseña) {
        $query = "SELECT * FROM usuarios WHERE correo = ? AND estado = 'activo' LIMIT 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            
            // Verificar contraseña
            if (md5($contraseña) === $usuario['contraseña']) {
                $this->id = $usuario['id'];
                $this->nombre = $usuario['nombre'];
                $this->correo = $usuario['correo'];
                $this->rol = $usuario['rol'];
                $this->estado = $usuario['estado'];
                
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Crear nuevo usuario
     */
    public function crear($nombre, $correo, $contraseña, $rol = 'funcionario') {
        // Verificar si el correo ya existe
        $query_check = "SELECT id FROM usuarios WHERE correo = ?";
        $stmt_check = $this->conexion->prepare($query_check);
        $stmt_check->bind_param("s", $correo);
        $stmt_check->execute();
        
        if ($stmt_check->get_result()->num_rows > 0) {
            return ['exito' => false, 'mensaje' => 'El correo ya está registrado'];
        }
        
        $contraseña_hash = md5($contraseña);
        $query = "INSERT INTO usuarios (nombre, correo, contraseña, rol) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ssss", $nombre, $correo, $contraseña_hash, $rol);
        
        if ($stmt->execute()) {
            return ['exito' => true, 'id' => $this->conexion->insert_id, 'mensaje' => 'Usuario creado exitosamente'];
        } else {
            return ['exito' => false, 'mensaje' => 'Error al crear usuario: ' . $stmt->error];
        }
    }
    
    /**
     * Obtener usuario por ID
     */
    public function obtenerPorId($id) {
        $query = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Obtener todos los usuarios
     */
    public function obtenerTodos() {
        $query = "SELECT * FROM usuarios ORDER BY nombre ASC";
        $resultado = $this->conexion->query($query);
        
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Actualizar usuario
     */
    public function actualizar($id, $nombre, $correo, $rol, $estado) {
        $query = "UPDATE usuarios SET nombre = ?, correo = ?, rol = ?, estado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ssssi", $nombre, $correo, $rol, $estado, $id);
        
        if ($stmt->execute()) {
            return ['exito' => true, 'mensaje' => 'Usuario actualizado'];
        } else {
            return ['exito' => false, 'mensaje' => 'Error al actualizar: ' . $stmt->error];
        }
    }
    
    /**
     * Cambiar contraseña
     */
    public function cambiarContraseña($id, $contraseña_nueva) {
        $contraseña_hash = md5($contraseña_nueva);
        $query = "UPDATE usuarios SET contraseña = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("si", $contraseña_hash, $id);
        
        if ($stmt->execute()) {
            return ['exito' => true, 'mensaje' => 'Contraseña actualizada'];
        } else {
            return ['exito' => false, 'mensaje' => 'Error al actualizar contraseña'];
        }
    }
}
?>
