#!/usr/bin/env bash

# ====================================================================
# SCRIPT DE INSTALACIÓN - SISTEMA DE VISITANTES
# ====================================================================
# Este script ayuda a configurar el sistema automáticamente
# Requiere: bash, mysql, php

set -e

echo "================================================"
echo "  INSTALADOR - Sistema de Registro de Visitantes"
echo "================================================"
echo ""

# Colores para el output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Verificar si mysql está instalado
if ! command -v mysql &> /dev/null; then
    echo -e "${RED}✗ MySQL no está instalado${NC}"
    echo "Instala MySQL e intenta de nuevo"
    exit 1
fi

echo -e "${GREEN}✓ MySQL encontrado${NC}"

# Solicitar datos de conexión
echo ""
echo "Ingresa los datos de conexión a la base de datos:"
read -p "Usuario MySQL (default: root): " DB_USER
DB_USER=${DB_USER:-root}

read -sp "Contraseña MySQL: " DB_PASS
echo ""

read -p "Host (default: localhost): " DB_HOST
DB_HOST=${DB_HOST:-localhost}

read -p "Puerto (default: 3306): " DB_PORT
DB_PORT=${DB_PORT:-3306}

# Crear base de datos
echo ""
echo "Creando base de datos..."

mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" -P "$DB_PORT" < database/schema.sql

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Base de datos creada exitosamente${NC}"
else
    echo -e "${RED}✗ Error al crear base de datos${NC}"
    exit 1
fi

# Crear config.php
echo ""
echo "Creando archivo de configuración..."

cat > config.php <<EOF
<?php
define('DB_HOST', '$DB_HOST');
define('DB_USER', '$DB_USER');
define('DB_PASS', '$DB_PASS');
define('DB_NAME', 'sistema_visitantes');
define('DB_PORT', $DB_PORT);

define('BASE_URL', 'http://localhost/Practica-Semana6');
define('VIEWS_PATH', __DIR__ . '/app/views/');
define('MODELS_PATH', __DIR__ . '/app/models/');
define('CONTROLLERS_PATH', __DIR__ . '/app/controllers/');

try {
    \$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if (\$conn->connect_error) {
        throw new Exception("Error de conexión: " . \$conn->connect_error);
    }
    \$conn->set_charset("utf8mb4");
} catch (Exception \$e) {
    die("Conexión fallida: " . \$e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
EOF

echo -e "${GREEN}✓ config.php creado${NC}"

# Verificar estructura de carpetas
echo ""
echo "Verificando estructura de carpetas..."

CARPETAS=(
    "app/models"
    "app/controllers"
    "app/views"
    "public/css"
    "public/js"
    "database"
)

for carpeta in "${CARPETAS[@]}"; do
    if [ -d "$carpeta" ]; then
        echo -e "${GREEN}✓ $carpeta${NC}"
    else
        echo -e "${YELLOW}⚠ Carpeta $carpeta no encontrada${NC}"
    fi
done

# Resumen final
echo ""
echo "================================================"
echo -e "${GREEN}✓ INSTALACIÓN COMPLETADA${NC}"
echo "================================================"
echo ""
echo "Próximos pasos:"
echo "1. Inicia un servidor web o usa:"
echo "   php -S localhost:8000"
echo ""
echo "2. Accede a:"
echo "   http://localhost:8000/index.php"
echo ""
echo "3. Usa estas credenciales:"
echo "   Email: admin@visitantes.com"
echo "   Contraseña: admin123"
echo ""
echo "Para más información, ver README.md"
echo ""
