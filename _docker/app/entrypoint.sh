#!/bin/bash

set -e

echo "===> Entrypoint is running..."
echo "Container role: $CONTAINER_ROLE"

if [ "$CONTAINER_ROLE" = "app" ]; then

    if [ ! -f .env ]; then
        echo "No .env found. Copying .env.example..."
        # cp .env.example .env    # можеш розкоментувати, якщо хочеш автоматично копіювати
        echo "Please create a .env file before continuing."
        exit 1
    fi

    # install dependencies
    if [ ! -d "vendor" ]; then
        echo "Running composer install..."
        composer install --no-interaction --prefer-dist --optimize-autoloader
    fi

    # Gen APP_KEY if not exists
    if ! grep -q "^APP_KEY=base64" .env 2>/dev/null; then
        echo "Generating APP_KEY..."
        php artisan key:generate
    fi

    # caching config and routes
    php artisan config:cache
    php artisan route:cache

    exec php-fpm

elif [ "$CONTAINER_ROLE" = "queue" ]; then
    exec php artisan queue:work --tries=3 --sleep=3
elif [ "$CONTAINER_ROLE" = "scheduler" ]; then
    exec php artisan schedule:work
else
    echo "Error: Unknown container role: $CONTAINER_ROLE"
    exit 1
fi
