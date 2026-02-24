#!/usr/bin/env bash

# Set the working directory
cd /var/www/html

# Ensure the correct permissions for storage and cache directories
chown -R www-data:www-data storage bootstrap/cache

# Copy .env.example if .env does not exist.
# if [ ! -f .env ]; then
#     ln -s environments/.env.develop.env .env
# fi

# Generate the application key.
/usr/local/bin/php artisan key:generate

# Run database migrations and other Artisan commands
/usr/local/bin/php artisan cache:clear
/usr/local/bin/php artisan config:clear
/usr/local/bin/php artisan migrate --force
/usr/local/bin/php artisan storage:link
/usr/local/bin/php artisan install:api

# Start the main Apache process in the foreground to keep the container running.
exec apachectl -D FOREGROUND