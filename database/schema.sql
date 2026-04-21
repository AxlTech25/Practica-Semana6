-- Crear base de datos
CREATE DATABASE IF NOT EXISTS sistema_visitantes;
USE sistema_visitantes;

-- Tabla de usuarios (Seguridad - Login)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    correo VARCHAR(255) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'funcionario', 'seguridad') DEFAULT 'funcionario',
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de despachos
CREATE TABLE IF NOT EXISTS despachos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL UNIQUE,
    responsable VARCHAR(255) NOT NULL,
    piso INT,
    edificio VARCHAR(100),
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de visitantes
CREATE TABLE IF NOT EXISTS visitantes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre_completo VARCHAR(255) NOT NULL,
    documento_identidad VARCHAR(20) UNIQUE NOT NULL,
    tipo_documento ENUM('cedula', 'pasaporte', 'otro') DEFAULT 'cedula',
    persona_visitada VARCHAR(255) NOT NULL,
    despacho_visitado INT NOT NULL,
    fecha_visita DATE NOT NULL,
    hora_entrada TIME NOT NULL,
    hora_salida TIME,
    tiempo_permanencia VARCHAR(50),
    motivo_visita TEXT,
    observaciones TEXT,
    estado ENUM('activa', 'finalizada', 'anulada') DEFAULT 'activa',
    usuario_registro INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (despacho_visitado) REFERENCES despachos(id),
    FOREIGN KEY (usuario_registro) REFERENCES usuarios(id),
    INDEX idx_documento (documento_identidad),
    INDEX idx_fecha (fecha_visita),
    INDEX idx_despacho (despacho_visitado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de reportes/logs de acceso
CREATE TABLE IF NOT EXISTS logs_acceso (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    accion VARCHAR(255),
    tabla_afectada VARCHAR(100),
    registro_id INT,
    detalles TEXT,
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar usuario administrador por defecto
INSERT INTO usuarios (nombre, correo, contraseña, rol) 
VALUES ('Administrador', 'admin@visitantes.com', MD5('admin123'), 'admin')
ON DUPLICATE KEY UPDATE contraseña = MD5('admin123');

-- Insertar despachos de ejemplo
INSERT INTO despachos (nombre, responsable, piso, edificio) VALUES
('Dirección General', 'Dr. García López', 1, 'Principal'),
('Recursos Humanos', 'Lic. María Rodríguez', 2, 'Principal'),
('Contabilidad', 'Lic. Juan Pérez', 2, 'Principal'),
('Vigilancia y Seguridad', 'Sgto. Carlos Ruiz', 1, 'Principal'),
('Informática', 'Ing. Ana Martínez', 3, 'Principal');
