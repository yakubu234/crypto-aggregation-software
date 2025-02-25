#!/bin/bash

# starting the php fpm
echo "Starting PHP FPM..."
php-fpm &

# Replace .env file with .env.docker (force replace every time)
echo "Replacing .env with .env.docker..."
cp -f /var/www/.docker-env /var/www/.env

# Install Laravel dependencies
composer install --no-interaction --no-progress --optimize-autoloader

# Generate app key if not already set
if [ ! -f /var/www/storage/oauth-private.key ]; then
    echo "Generating Laravel app key..."
    php artisan key:generate
fi

# Run migrations and seed
echo "Running migrations..."
php artisan migrate --force

# Ensure storage is linked
php artisan storage:link || true
# Wait for database to be ready
echo "Waiting for MySQL to be ready..."
until mysqladmin ping -h"$DB_HOST" --silent; do
    sleep 2
done
echo "MySQL is ready!"

echo "Removing the hot folder in public directory"
rm -f /var/www/public/hot

# Start Supervisor to run Queue, Reverb, Scheduler
echo "Starting Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf

echo "Starting Laravel application..."

# Final Notification
echo "" # Newline for readability
echo "----------------------------------------"
echo -e "\n✅ Laravel application setup complete!"
echo "You can now access your application at:"
echo "http://localhost:8080" # Or your actual URL
echo "----------------------------------------"


