# 🏢 Sistema de Registro de Visitantes

> Sistema web MVC completo para registro, control y análisis de visitantes en instituciones.

## ✨ Características

## Integrantes
- Rojas Camayo Valentino
- Estrada Flores Axel

### 📝 Registro de Visitantes
- Captura de datos completos: nombre, documento, despacho
- Control automático de entrada/salida
- Cálculo automático de tiempo de permanencia
- Motivo de visita y observaciones

### 🔐 Seguridad
- Sistema de login con roles de usuario
- Tres roles: Admin, Funcionario, Seguridad
- Validación de sesiones en todas las páginas
- Registro de auditoría de todas las acciones

### 🔍 Consultas y Búsqueda
- Búsqueda por documento, nombre, fecha, despacho
- Listado paginado de visitantes
- Filtros avanzados
- Vista detallada de cada registro

### 📊 Reportes y Análisis
- Dashboard con estadísticas generales
- Visitas por día
- Visitas por despacho
- Tiempo promedio de permanencia
- Horas pico de visitas
- Historial detallado

### 💾 Exportación de Datos
- Exportar a CSV
- Exportar a Excel (.xls)
- Múltiples formatos de reporte

---

## 🛠️ Stack Técnico

| Componente | Tecnología |
|-----------|-----------|
| **Backend** | PHP 7.4+ |
| **Base de Datos** | MySQL 5.7+ |
| **Frontend** | HTML5, CSS3, JavaScript ES6 |
| **Framework CSS** | Bootstrap 5 |
| **Iconos** | Bootstrap Icons |

---

## 📦 Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache con mod_rewrite (opcional)
- Navegador web moderno (Chrome, Firefox, Safari, Edge)

---

## 🚀 Instalación Rápida

### 1️⃣ Clonar o descargar proyecto
```bash
git clone https://github.com/usuario/Practica-Semana6.git
cd Practica-Semana6
```

### 2️⃣ Crear base de datos
```bash
mysql -u root -p < database/schema.sql
```

### 3️⃣ Configurar conexión (config.php)
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'contraseña');
define('DB_NAME', 'sistema_visitantes');
```

### 4️⃣ Acceder a la aplicación
```
http://localhost/Practica-Semana6/
```

**Credenciales Demo:**
- Email: `admin@visitantes.com`
- Contraseña: `admin123`

---

## 📁 Estructura del Proyecto

```
Practica-Semana6/
├── index.php                    ← Punto de entrada
├── config.php                   ← Configuración
├── BACKEND_DOCUMENTACION.md     ← Docs técnico (Importante!)
│
├── app/
│   ├── models/                  (Capa de datos)
│   │   ├── Usuario.php
│   │   ├── Visitante.php
│   │   ├── Despacho.php
│   │   └── Reporte.php
│   │
│   ├── controllers/             (Lógica de negocio)
│   │   ├── AutenticacionController.php
│   │   ├── VisitanteController.php
│   │   └── ReporteController.php
│   │
│   └── views/                   (Presentación)
│       ├── login.php
│       ├── dashboard.php
│       ├── registro_visitante.php
│       ├── busqueda_visitantes.php
│       └── [más vistas...]
│
├── database/
│   └── schema.sql               ← Script de BD
│
├── public/
│   ├── css/
│   │   └── estilos.css
│   └── js/
│       └── funciones.js
│
└── README.md                    (Este archivo)
```

---

## 🔐 Seguridad Implementada

✅ Validación de sesiones  
✅ Control de roles de usuario  
✅ Prepared statements (previene SQL injection)  
✅ Hash de contraseñas  
✅ Registro de auditoría  
✅ Validación de datos de entrada  

---

## 📚 Documentación Completa

Para documentación técnica detallada, ver: **[BACKEND_DOCUMENTACION.md](./BACKEND_DOCUMENTACION.md)**

Incluye:
- Estructura detallada del backend
- API de funciones
- Ejemplos de código
- Troubleshooting
- Y mucho más...

---

## 🎯 Flujo Principal

1. **Usuario inicia sesión** → Validación de credenciales
2. **Dashboard** → Selecciona acción
3. **Registrar visitante** → Captura de datos
4. **Búsqueda/Consultas** → Filtros avanzados
5. **Reportes** → Estadísticas y análisis
6. **Exportación** → Descarga de datos

---

## 🚦 Estados de Visitante

| Estado | Descripción |
|--------|------------|
| **Activa** | Visitante dentro de las instalaciones |
| **Finalizada** | Salida registrada |
| **Anulada** | Registro cancelado |

---

## 💡 Funcionalidades por Rol

| Funcionalidad | Admin | Funcionario | Seguridad |
|---|---|---|---|
| Registrar visitantes | ✅ | ✅ | ❌ |
| Buscar visitantes | ✅ | ✅ | ✅ |
| Ver lista completa | ✅ | ✅ | ✅ |
| Ver reportes | ✅ | ❌ | ✅ |
| Exportar datos | ✅ | ❌ | ✅ |

---

## 🔄 Arquitectura MVC

```
  Request
    ↓
index.php (Front Controller)
    ↓
Controller (Lógica de negocio)
    ↓
Model (Base de datos)
    ↓
View (Presentación)
    ↓
Response (HTML)
```

---

## 📊 Reportes Disponibles

- **Estadísticas Generales**: Total de visitas, visitantes únicos
- **Visitas por Día**: Análisis diario
- **Visitas por Despacho**: Distribución por área
- **Tiempo Promedio**: Duración media de visitas
- **Horas Pico**: Horarios con más afluencia
- **Historial Detallado**: Registro de todas las visitas

---

## 📱 Responsive Design

✅ Desktop (1920x1080+)
✅ Laptop (1366x768)
✅ Tablet (768x1024)
✅ Mobile (375x667+)

---

## 🐛 Solución de Problemas

### Error: "No se puede conectar a la base de datos"
```bash
# Verificar MySQL
mysql -u root -p -e "SHOW DATABASES;"

# Importar schema
mysql -u root -p sistema_visitantes < database/schema.sql
```

### Error: "Tabla no encontrada"
```bash
# Asegúrate de que la BD está creada y las tablas existen
mysql -u root -p sistema_visitantes -e "SHOW TABLES;"
```

Más soluciones en [BACKEND_DOCUMENTACION.md](./BACKEND_DOCUMENTACION.md)

---

## 📝 Versión

- **v1.0** - Release inicial
- **Estado**: ✅ Producción
- **Última actualización**: Enero 2025

---

## 📞 Soporte

Para reportar bugs o sugerencias, contacta al administrador del sistema.

---

<div align="center">

**Sistema de Registro de Visitantes v1.0**  
Desarrollado con ❤️ para gestión eficiente de acceso

</div>