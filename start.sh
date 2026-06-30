#!/bin/sh
set -e

# Default port jika $PORT tidak di-set
PORT=${PORT:-8080}

echo "=== LMS Trimurti Husada ==="
echo "Port: $PORT"
echo "Environment: $APP_ENV"

# Clear config cache
php artisan config:clear 2>/dev/null || true

# Run migrations
echo "Running migrations..."
php artisan migrate --force 2>&1 || echo "Migration errors (continuing...)"

# Create storage symlink
php artisan storage:link 2>/dev/null || true

# Cache config for production
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true

echo "Starting PHP built-in server on 0.0.0.0:$PORT"
exec php -S "0.0.0.0:$PORT" -t public
