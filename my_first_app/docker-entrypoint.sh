#!/bin/sh
set -e

# Substitute PORT environment variable into Nginx configuration (default 10000)
PORT="${PORT:-10000}"
sed -i "s/PORT_PLACEHOLDER/${PORT}/g" /etc/nginx/http.d/default.conf

# Cache Laravel configurations for production
echo "Caching Laravel configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations if DB_HOST is present
if [ -n "$DB_HOST" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || echo "Migration warning: check DB logs"
fi

# Start PHP-FPM in background
echo "Starting PHP-FPM daemon..."
php-fpm -D

# Start Nginx in foreground
echo "Starting Nginx web server on port ${PORT}..."
exec nginx -g 'daemon off;'
