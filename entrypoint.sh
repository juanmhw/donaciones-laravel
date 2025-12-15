#!/bin/bash
set -e

# Copiar .env si no existe
if [ ! -f .env ]; then
    echo "📄 Creando .env desde .env.example..."
    cp .env.example .env
fi

# Instalar dependencias PHP
echo "📦 Instalando dependencias de Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Permisos (Crítico para que Laravel escriba logs y sesiones)
echo "🔒 Asignando permisos..."
chmod -R 777 storage bootstrap/cache

# Generar Key si falta
php artisan key:generate --force

# Migraciones
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

# --- AQUÍ ESTÁ LO QUE FALTABA ---
echo "🌱 Ejecutando Seeders..."
php artisan db:seed --force
# --------------------------------

# Enlace simbólico almacenamiento (para imágenes públicas)
php artisan storage:link

echo "🚀 Iniciando PHP-FPM..."
exec php-fpm