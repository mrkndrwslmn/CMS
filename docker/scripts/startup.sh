#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Ensure storage directories exist and have proper permissions
mkdir -p /var/www/html/storage/framework/{cache,sessions,views}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Check if caches exist, if not create them
if [ ! -f /var/www/html/bootstrap/cache/config.php ]; then
    echo "📦 Generating config cache..."
    php artisan config:cache
fi

if [ ! -f /var/www/html/bootstrap/cache/routes-v7.php ]; then
    echo "📦 Generating route cache..."
    php artisan route:cache
fi

# Check if view cache is populated (check for any compiled views)
VIEW_CACHE_COUNT=$(find /var/www/html/storage/framework/views -name "*.php" 2>/dev/null | wc -l)
if [ "$VIEW_CACHE_COUNT" -lt 10 ]; then
    echo "📦 Generating view cache (this may take a moment on first run)..."
    php artisan view:cache
fi

# Check if blade-icons cache exists
if [ ! -f /var/www/html/bootstrap/cache/blade-icons.php ]; then
    echo "📦 Generating blade-icons cache..."
    php artisan icons:cache 2>/dev/null || true
fi

echo "✅ Laravel caches ready!"

# Start supervisor (which manages Apache, Vite, Queue workers, etc.)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
