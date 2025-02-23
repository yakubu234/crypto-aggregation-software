#!/bin/bash

# Wait for database to be ready
echo "Waiting for MySQL to be ready..."
until mysqladmin ping -h"$DB_HOST" --silent; do
    sleep 2
done
echo "MySQL is ready!"

# Install Laravel dependencies
echo "Running composer install..."
composer install --no-dev --optimize-autoloader

# Generate app key if not already set
if [ ! -f /var/www/storage/oauth-private.key ]; then
    echo "Generating Laravel app key..."
    php artisan key:generate
fi

# Run migrations and seed
echo "Running migrations..."
php artisan migrate --seed --force

# Ensure storage is linked
php artisan storage:link || true

# Start Supervisor to run Queue, Reverb, Scheduler
echo "Starting Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
