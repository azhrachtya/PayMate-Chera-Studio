#!/usr/bin/env bash
cd /var/www/html

php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deploy selesai"