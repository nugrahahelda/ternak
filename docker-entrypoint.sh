#!/bin/bash
set -e

echo "==> Clearing cache..."
php artisan config:clear
php artisan cache:clear

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Starting server on port ${PORT:-8000}..."
exec php -S 0.0.0.0:${PORT:-8000} -t public