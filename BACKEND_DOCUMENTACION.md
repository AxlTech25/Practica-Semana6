# 📋 Sistema de Registro de Visitantes - DOCUMENTACIÓN DEL BACKEND

## 📑 Contenido
1. [Estructura del Proyecto](#estructura)
2. [Instalación](#instalación)
3. [Configuración](#configuración)
4. [Base de Datos](#base-de-datos)
5. [Modelos](#modelos)
6. [Controladores](#controladores)
7. [Rutas y Enrutamiento](#rutas)
8. [API de Funciones](#api)
9. [Seguridad](#seguridad)
10. [Troubleshooting](#troubleshooting)

---

## <a id="estructura"></a>📁 Estructura del Proyecto

```
Practica-Semana6/
├── index.php                    # Archivo principal - Enrutador MVC
├── config.php                   # Configuración de la aplicación
│
├── app/
│   ├── models/                  # Modelos de datos
│   │   ├── Usuario.php          # Gestión de usuarios y autenticación
│   │   ├── Visitante.php        # Gestión de visitantes
│   │   ├── Despacho.php         # Gestión de despachos
│   │   └── Reporte.php          # Generación de reportes
│   │
│   ├── controllers/             # Controladores (lógica de negocio)
│   │   ├── AutenticacionController.php
│   │   ├── VisitanteController.php
│   │   └── ReporteController.php
│   │
│   └── views/                   # Vistas (presentación)
│       ├── login.php
│       ├── dashboard.php
│       ├── registro_visitante.php
│       ├── busqueda_visitantes.php
│       ├── lista_visitantes.php
│       ├── detalles_visitante.php
│       ├── editar_visitante.php
│       ├── reportes_dashboard.php
│       ├── historial_visitas.php
│       └── componentes/
│           ├── navbar.php
│           └── sidebar.php
│
├── database/
│   └── schema.sql               # Script de creación de BD
│
├── public/
│   ├── css/
│   │   └── estilos.css
│   └── js/
│       └── funciones.js
│
└── README.md                    # Este archivo
```

---

## <a id="instalación"></a>🔧 Instalación

### Requisitos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache con mod_rewrite (opcional)
- Navegador web moderno

### Pasos de Instalación

1. **Clonar o descargar el proyecto**
```bash
# Si está en un repositorio
git clone https://github.com/usuario/Practica-Semana6.git
cd Practica-Semana6
```

2. **Crear la carpeta en htdocs (si usa XAMPP/WAMP)**
```bash
# XAMPP Linux/Mac
cp -r Practica-Semana6 /opt/lampp/htdocs/

# XAMPP Windows
xcopy Practica-Semana6 C:\xampp\htdocs\Practica-Semana6\ /E

# WAMP Windows
xcopy Practica-Semana6 C:\wamp64\www\Practica-Semana6\ /E
```

3. **Crear la base de datos**
```bash
# Opción 1: Desde línea de comandos
mysql -u root -p < database/schema.sql

# Opción 2: Desde phpMyAdmin
# - Crear base de datos: sistema_visitantes
# - Importar: database/schema.sql
```

4. **Configurar la conexión de BD**
- Editar `config.php`
- Cambiar las constantes según tu instalación:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Tu usuario
define('DB_PASS', 'root');      // Tu contraseña
define('DB_NAME', 'sistema_visitantes');
define('DB_PORT', 3306);
```

5. **Acceder a la aplicación**
```
http://localhost/Practica-Semana6/index.php
```

**Credenciales de prueba:**
- Email: `admin@visitantes.com`
- Contraseña: `admin123`

---

## <a id="configuración"></a>⚙️ Configuración

### Archivo config.php

Define las constantes globales de la aplicación:

```php
define('DB_HOST', 'localhost');         // Host de la BD
define('DB_USER', 'root');              // Usuario BD
define('DB_PASS', 'root');              // Contraseña BD
define('DB_NAME', 'sistema_visitantes'); // Nombre de la BD
define('DB_PORT', 3306);                // Puerto

define('BASE_URL', 'http://localhost/Practica-Semana6');
define('VIEWS_PATH', __DIR__ . '/app/views/');
define('MODELS_PATH', __DIR__ . '/app/models/');
define('CONTROLLERS_PATH', __DIR__ . '/app/controllers/');
```

---

## <a id="base-de-datos"></a>🗄️ Base de Datos

### Tablas Principales

#### 1. Tabla: `usuarios`
Gestiona autenticación y usuarios del sistema.

```sql
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    correo VARCHAR(255) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'funcionario', 'seguridad'),
    estado ENUM('activo', 'inactivo'),
    fecha_creacion TIMESTAMP
);
```

#### 2. Tabla: `visitantes`
Registra todas las visitas.

```sql
CREATE TABLE visitantes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre_completo VARCHAR(255) NOT NULL,
    documento_identidad VARCHAR(20) UNIQUE NOT NULL,
    tipo_documento ENUM('cedula', 'pasaporte', 'otro'),
    persona_visitada VARCHAR(255) NOT NULL,
    despacho_visitado INT NOT NULL,
    fecha_visita DATE NOT NULL,
    hora_entrada TIME NOT NULL,
    hora_salida TIME,
    tiempo_permanencia VARCHAR(50),
    motivo_visita TEXT,
    observaciones TEXT,
    estado ENUM('activa', 'finalizada', 'anulada'),
    usuario_registro INT,
    fecha_creacion TIMESTAMP
);
```

#### 3. Tabla: `despachos`
Información de departamentos/despachos.

```sql
CREATE TABLE despachos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL UNIQUE,
    responsable VARCHAR(255) NOT NULL,
    piso INT,
    edificio VARCHAR(100),
    estado ENUM('activo', 'inactivo'),
    fecha_creacion TIMESTAMP
);
```

#### 4. Tabla: `logs_acceso`
Registro de auditoría del sistema.

```sql
CREATE TABLE logs_acceso (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    accion VARCHAR(255),
    tabla_afectada VARCHAR(100),
    registro_id INT,
    detalles TEXT,
    fecha_hora TIMESTAMP
);
```

---

## <a id="modelos"></a>📊 Modelos

### Clase: Usuario
**Archivo:** `app/models/Usuario.php`

```php
// Constructor
$usuario = new Usuario($conexion);

// Métodos principales
$usuario->autenticar($correo, $contrasena)      // Autentica usuario
$usuario->crear($nombre, $correo, $pass, $rol)  // Crea nuevo usuario
$usuario->obtenerPorId($id)                     // Obtiene un usuario
$usuario->obtenerTodos()                        // Lista todos los usuarios
$usuario->actualizar($id, $nombre, ...)         // Actualiza usuario
$usuario->cambiarContraseña($id, $nueva_pass)   // Cambia contraseña
```

### Clase: Visitante
**Archivo:** `app/models/Visitante.php`

```php
$visitante = new Visitante($conexion);

// Métodos principales
$visitante->registrar($datos)                   // Registra nuevo visitante
$visitante->registrarSalida($id, $hora_salida)  // Registra hora de salida
$visitante->buscar($criterios)                  // Busca visitantes
$visitante->obtenerPorId($id)                   // Obtiene un visitante
$visitante->obtenerTodos($limite, $offset)      // Lista visitantes (paginado)
$visitante->actualizar($id, $datos)             // Actualiza visitante
$visitante->eliminar($id)                       // Anula visitante
```

### Clase: Despacho
**Archivo:** `app/models/Despacho.php`

```php
$despacho = new Despacho($conexion);

$despacho->obtenerTodos($solo_activos)          // Lista despachos
$despacho->obtenerPorId($id)                    // Obtiene un despacho
$despacho->crear($nombre, $responsable, ...)    // Crea despacho
```

### Clase: Reporte
**Archivo:** `app/models/Reporte.php`

```php
$reporte = new Reporte($conexion);

// Métodos de reportes
$reporte->visitasPorDia($fecha_inicio, $fecha_fin)
$reporte->visitasPorDespacho($fecha_inicio, $fecha_fin)
$reporte->tiempoPromedioPermanencia($fecha_inicio, $fecha_fin)
$reporte->historialVisitas($fecha_inicio, $fecha_fin, $despacho_id)
$reporte->estadisticasGenerales($fecha_inicio, $fecha_fin)
$reporte->horasPicoVisitas($fecha_inicio, $fecha_fin)
```

---

## <a id="controladores"></a>🎮 Controladores

### AutenticacionController
**Archivo:** `app/controllers/AutenticacionController.php`

Gestiona login, logout y validación de sesiones.

```php
// Métodos públicos
mostrarLogin()              // Muestra formulario de login
procesarLogin()             // Procesa credenciales
logout()                    // Cierra sesión

// Métodos estáticos
AutenticacionController::verificarSesion()      // Verifica si está logueado
AutenticacionController::verificarRol($roles)   // Verifica rol de usuario
```

### VisitanteController
**Archivo:** `app/controllers/VisitanteController.php`

Gestiona operaciones con visitantes.

```php
mostrarRegistro()           // Muestra formulario de registro
procesarRegistro()          // Procesa nuevo registro
mostrarLista()              // Muestra lista de visitantes
mostrarBusqueda()           // Muestra formulario de búsqueda
mostrarDetalles()           // Muestra detalles de visitante
mostrarEdicion()            // Muestra formulario de edición
registrarSalida()           // Registra hora de salida
```

### ReporteController
**Archivo:** `app/controllers/ReporteController.php`

Genera reportes y análisis.

```php
mostrarDashboard()          // Dashboard con estadísticas
mostrarHistorial()          // Historial detallado
exportarCSV()               // Exporta a CSV
exportarExcel()             // Exporta a Excel
```

---

## <a id="rutas"></a>🛣️ Rutas y Enrutamiento

### Flujo de Enrutamiento

El archivo `index.php` actúa como controlador frontal (Front Controller).

**Parámetro:** `?pagina=` | **Parámetro:** `?accion=`

### Rutas de Autenticación

```
GET  index.php?pagina=login              → Mostrar formulario login
POST index.php?accion=procesarLogin      → Procesar login
GET  index.php?accion=logout             → Cerrar sesión
```

### Rutas de Visitantes

```
GET  index.php?pagina=dashboard          → Dashboard principal
GET  index.php?pagina=registro           → Formulario registro
POST index.php?accion=procesarRegistro   → Procesar registro

GET  index.php?pagina=lista              → Lista de visitantes
GET  index.php?pagina=lista&p=2          → Lista paginada (página 2)

GET  index.php?pagina=busqueda           → Formulario búsqueda
POST index.php?pagina=busqueda           → Procesar búsqueda

GET  index.php?pagina=detalles&id=5      → Ver detalles visitante
POST index.php?accion=registrarSalida    → Registrar hora salida

GET  index.php?pagina=editar&id=5        → Formulario edición
POST index.php?pagina=editar&id=5        → Procesar edición
```

### Rutas de Reportes

```
GET  index.php?pagina=reportes           → Dashboard reportes
GET  index.php?pagina=reportes&fecha_inicio=2025-01-01&fecha_fin=2025-12-31

GET  index.php?pagina=historial          → Historial de visitas
GET  index.php?pagina=historial&despacho=3
```

### Rutas de Exportación

```
GET  index.php?accion=exportarCSV&tipo=historial
GET  index.php?accion=exportarExcel&tipo=historial
GET  index.php?accion=exportarCSV&tipo=dia
GET  index.php?accion=exportarExcel&tipo=despacho
```

---

## <a id="api"></a>📚 API de Funciones

### Ejemplo: Registrar Visitante

```php
require_once 'config.php';
require_once 'app/models/Visitante.php';

$visitante = new Visitante($conn);

$datos = [
    'nombre_completo'     => 'Juan Pérez',
    'documento_identidad' => '12345678',
    'tipo_documento'      => 'cedula',
    'persona_visitada'    => 'Dr. García',
    'despacho_visitado'   => 1,
    'fecha_visita'        => '2025-01-15',
    'hora_entrada'        => '08:30:00',
    'motivo_visita'       => 'Cita',
    'observaciones'       => 'Ninguna',
    'usuario_registro'    => 1
];

$resultado = $visitante->registrar($datos);

if ($resultado['exito']) {
    echo "Visitante registrado. ID: " . $resultado['id'];
} else {
    echo "Error: " . $resultado['mensaje'];
}
```

### Ejemplo: Buscar Visitantes

```php
$criteros = [
    'documento'  => '12345678',
    'fecha'      => '2025-01-15',
    'despacho'   => 1,
    'visitante'  => 'Juan'
];

$resultados = $visitante->buscar($criterios);

foreach ($resultados as $registro) {
    echo $registro['nombre_completo'] . " - " . $registro['despacho_nombre'];
}
```

### Ejemplo: Generar Reporte

```php
require_once 'app/models/Reporte.php';

$reporte = new Reporte($conn);

// Obtener estadísticas
$stats = $reporte->estadisticasGenerales('2025-01-01', '2025-12-31');
echo "Total visitas: " . $stats['total_visitas'];

// Visitas por día
$dias = $reporte->visitasPorDia('2025-01-01', '2025-01-31');
foreach ($dias as $dia) {
    echo $dia['fecha_visita'] . ": " . $dia['total_visitas'];
}
```

---

## <a id="seguridad"></a>🔐 Seguridad

### Validación de Sesiones

Todas las páginas protegidas verifican sesión:

```php
require_once CONTROLLERS_PATH . 'AutenticacionController.php';
AutenticacionController::verificarSesion();
```

### Validación de Rol

Algunas páginas requieren rol específico:

```php
AutenticacionController::verificarRol(['admin', 'seguridad']);
```

### Protección contra SQL Injection

Se usan prepared statements en todos los modelos:

```php
$stmt = $conexion->prepare("SELECT * FROM visitantes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
```

### Gestión de Contraseñas

Las contraseñas se guardan hasheadas con MD5:

```php
$contraseña_hash = md5($contraseña);
```

**⚠️ Nota:** Para producción, usar `password_hash()` con bcrypt.

---

## <a id="troubleshooting"></a>🐛 Solución de Problemas

### Error: "No se puede conectar a la base de datos"

**Solución:**
1. Verificar que MySQL está ejecutándose
2. Revisar credentials en `config.php`
3. Verificar que la BD existe: `CREATE DATABASE sistema_visitantes;`

```bash
mysql -u root -p -e "SHOW DATABASES;"
```

### Error: "Error de conexión: Access denied"

**Solución:**
```bash
# Reset de contraseña MySQL
mysql -u root
USE mysql;
UPDATE user SET authentication_string=PASSWORD('nuevapass') WHERE User='root';
FLUSH PRIVILEGES;
```

### Error: "Tabla no encontrada"

**Solución:** Importar el schema de la BD:

```bash
mysql -u root -p sistema_visitantes < database/schema.sql
```

### Error: "Acción no permitida"

**Solución:** El usuario no tiene permisos. Verificar rol en BD:

```sql
SELECT id, nombre, rol FROM usuarios WHERE correo = 'email@example.com';
```

### Páginas en blanco

**Solución:**
1. Activar mostrar errores en `config.php`:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

2. Revisar logs de Apache/PHP

---

## 📞 Soporte

Para reportar bugs o sugerencias, contactar al administrador del sistema.

**Versión:** 1.0  
**Última actualización:** 2025-01-15  
**Autor:** Sistema de Visitantes
