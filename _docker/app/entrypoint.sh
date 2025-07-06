#!/bin/bash

set -e

echo "===> Entrypoint is running..."
echo "Container role: $CONTAINER_ROLE"

# Ensure bootstrap/cache exists
if [ ! -d "bootstrap/cache" ]; then
    echo "Creating bootstrap/cache directory..."
    mkdir -p bootstrap/cache
fi

chmod -R 775 bootstrap/cache
chown -R www-data:www-data bootstrap/cache

if [ "$CONTAINER_ROLE" = "app" ]; then

    echo "Creating Laravel storage directories..."
    mkdir -p storage/framework/{cache/data,sessions,views}
    chown -R www-data:www-data storage
    chmod -R 775 storage

    if [ ! -f .env ]; then
        echo "No .env found. Copying .env.example..."
        echo "Please create a .env file before continuing."
        exit 1
    fi

    # Gen APP_KEY if not exists
    if ! grep -q "^APP_KEY=base64" .env 2>/dev/null; then
        echo "Generating APP_KEY..."
        php artisan key:generate
    fi

    exec php-fpm

elif [ "$CONTAINER_ROLE" = "queue" ]; then
    exec php artisan queue:work --tries=3 --sleep=3
elif [ "$CONTAINER_ROLE" = "scheduler" ]; then
    exec php artisan schedule:work
else
    echo "Error: Unknown container role: $CONTAINER_ROLE"
    exit 1
fi
