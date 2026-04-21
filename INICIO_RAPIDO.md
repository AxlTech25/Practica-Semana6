## 🚀 GUÍA RÁPIDA DE INICIO - SISTEMA DE VISITANTES

### ⏰ Tiempo estimado: 5 minutos

---

## PASO 1️⃣: Descargar/Clonar el Proyecto

```bash
# Si tienes git:
git clone https://github.com/usuario/Practica-Semana6.git
cd Practica-Semana6

# O descarga como ZIP y extrae la carpeta
```

---

## PASO 2️⃣: Crear la Base de Datos

### Opción A: Línea de comandos (Recomendado)
```bash
mysql -u root -p < database/schema.sql
```

### Opción B: phpMyAdmin
1. Abre phpMyAdmin
2. Clic en "Nueva" → Crear BD "sistema_visitantes"
3. Clic en la BD creada
4. Clic en "Importar"
5. Selecciona `database/schema.sql`
6. Clic en "Continuar"

### Opción C: Script automático (Linux/Mac)
```bash
chmod +x install.sh
./install.sh
```

---

## PASO 3️⃣: Configurar Conexión

### Opción A: Editar config.php
```php
define('DB_HOST', 'localhost');  // Cambiar si es necesario
define('DB_USER', 'root');        // Tu usuario MySQL
define('DB_PASS', 'tu_password'); // Tu contraseña MySQL
define('DB_NAME', 'sistema_visitantes');
define('DB_PORT', 3306);
```

### Opción B: Usar config.example.php como base
```bash
cp config.example.php config.php
# Luego edita config.php con tus datos
```

---

## PASO 4️⃣: Acceder al Sistema

### Opción A: Servidor local (XAMPP/WAMP)
```
http://localhost/Practica-Semana6/
```

### Opción B: Servidor PHP nativo
```bash
php -S localhost:8000
# Luego accede a: http://localhost:8000/
```

### Opción C: Docker (Opcional)
```bash
docker run -d -p 80:80 -v $(pwd):/var/www/html php:apache
```

---

## PASO 5️⃣: Login

Usa estas credenciales demo:
```
📧 Email:     admin@visitantes.com
🔐 Password:  admin123
```

---

## ✅ ¡LISTO!

Ahora puedes:
- ✅ Registrar visitantes
- ✅ Buscar visitantes
- ✅ Ver reportes
- ✅ Exportar datos

---

## 🎯 Primeras Acciones Recomendadas

### 1. Registrar un Visitante
1. Clic en "Nuevo Registro"
2. Completa el formulario
3. Clic en "Registrar Visitante"

### 2. Registrar Salida
1. Clic en "Buscar Visitante"
2. Busca el visitante que registraste
3. Clic en "Ver"
4. Ingresa hora de salida
5. Clic en "Registrar Salida"
→ **¡El sistema calcula automáticamente el tiempo!**

### 3. Ver Reportes
1. Clic en "Reportes" (solo admin/seguridad)
2. Selecciona rango de fechas
3. Visualiza estadísticas

### 4. Exportar Datos
1. Desde reportes, clic en "CSV" o "Excel"
2. Se descargará automáticamente

---

## 🔑 Usuarios Demo Disponibles

| Email | Contraseña | Rol | Acceso |
|-------|-----------|-----|--------|
| admin@visitantes.com | admin123 | Admin | Todo |
| func@visitantes.com | func123 | Funcionario | Registro, Búsqueda |
| seg@visitantes.com | seg123 | Seguridad | Reportes, Búsqueda |

**Nota:** Créalos en la BD o desde Admin

---

## 📖 Documentación Disponible

```
├── README.md                   ← Lee esto primero
├── BACKEND_DOCUMENTACION.md    ← Documentación técnica completa
├── ARQUITECTURA_BACKEND.md     ← Cómo funciona internamente
├── PROYECTO_RESUMEN.md         ← Resumen ejecutivo
└── ESTRUCTURA_ARCHIVOS.md      ← Dónde está cada cosa
```

---

## 🐛 Problemas Comunes

### ❌ "No se puede conectar a la base de datos"

**Solución:**
1. Verifica que MySQL está ejecutándose
2. Revisa credentials en config.php
3. Asegúrate que BD existe:
```bash
mysql -u root -p -e "SHOW DATABASES;" | grep sistema_visitantes
```

### ❌ "Tabla no encontrada"

**Solución:**
```bash
mysql -u root -p sistema_visitantes < database/schema.sql
```

### ❌ "Página en blanco"

**Solución:**
1. Abre navegador → F12 → Consola
2. Busca errores en rojo
3. Or activa debug en config.php:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

### ❌ "Error 404 - Archivo no encontrado"

**Solución:**
Asegúrate que tienes `index.php` en raíz del proyecto

Más soluciones en: **BACKEND_DOCUMENTACION.md**

---

## 🔐 Cambiar Contraseña (Admin)

```sql
UPDATE usuarios SET contraseña = MD5('nueva_contraseña') 
WHERE correo = 'admin@visitantes.com';
```

---

## 📊 Estructura Rápida

```
Practica-Semana6/
├── index.php ..................... Punto de entrada
├── config.php .................... Configuración
├── app/
│   ├── models/ ................... Datos
│   ├── controllers/ .............. Lógica
│   └── views/ .................... Interfaz
├── database/schema.sql ........... Base de datos
├── public/ ....................... Assets (CSS, JS)
└── 📚 Documentación (varios .md)
```

---

## ⚙️ Parámetros URL Útiles

### Dashboard
```
http://localhost/Practica-Semana6/?pagina=dashboard
```

### Registro
```
http://localhost/Practica-Semana6/?pagina=registro
```

### Búsqueda
```
http://localhost/Practica-Semana6/?pagina=busqueda
```

### Reportes
```
http://localhost/Practica-Semana6/?pagina=reportes
```

### Historial
```
http://localhost/Practica-Semana6/?pagina=historial
```

### Login
```
http://localhost/Practica-Semana6/?pagina=login
```

### Logout
```
http://localhost/Practica-Semana6/?accion=logout
```

---

## 💾 Hacer Respaldo

```bash
# Respaldo de BD
mysqldump -u root -p sistema_visitantes > backup.sql

# Restaurar BD
mysql -u root -p sistema_visitantes < backup.sql

# Respaldo de proyecto
tar -czf proyecto_backup.tar.gz Practica-Semana6/
```

---

## 🔄 Flujo Típico de Uso

```
1. Admin prepara sistema
   ↓
2. Funcionario registra visitante
   ↓
3. Visitante ingresa
   ↓
4. Al salir, registra hora de salida
   → Sistema calcula tiempo automáticamente
   ↓
5. Seguridad visualiza reportes
   ↓
6. Exporta datos en Excel/CSV
```

---

## 🔍 Verificar Instalación

```bash
# Comprobar que existen los archivos
ls -la app/models/
ls -la app/controllers/
ls -la app/views/
ls -la database/schema.sql

# Comprobar conexión MySQL
mysql -u root -p -e "SHOW DATABASES;"
mysql -u root -p -e "USE sistema_visitantes; SHOW TABLES;"
```

---

## 🎓 Próximos Pasos (Opcionales)

1. Crear más usuarios
2. Personalizar formularios
3. Agregar logos/estilos
4. Configurar notificaciones
5. Integrar con sistemas externos
6. Mejorar reportes

---

## 📞 Necesitas Ayuda?

1. **Consulta la documentación**
   - `README.md` - General
   - `BACKEND_DOCUMENTACION.md` - Técnico
   - `ARQUITECTURA_BACKEND.md` - Cómo funciona

2. **Revisa logs**
   - Apache: `/var/log/apache2/error.log`
   - MySQL: `/var/log/mysql/error.log`

3. **Base de datos**
   - `database/schema.sql` - Estructura
   - `logs_acceso` tabla - Auditoría

---

## ✨ ¡Ahora estás listo!

```
╔════════════════════════════════════╗
║  SISTEMA DE VISITANTES INSTALADO  ║
║           ✅ LISTO PARA USAR       ║
╚════════════════════════════════════╝
```

**Acceso:** http://localhost/Practica-Semana6/
**Login:** admin@visitantes.com / admin123

---

**¿Necesitas más ayuda?** Ver documentación técnica: `BACKEND_DOCUMENTACION.md`

**Versión:** 1.0  
**Estado:** ✅ Producción
