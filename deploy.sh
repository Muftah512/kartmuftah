#!/bin/bash

# 1. Update code from repository
git pull origin main

# 2. Install dependencies
composer install --optimize-autoloader --no-dev

# 3. Update database
php artisan migrate --force

# 4. Clear cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Set permissions
chown -R www-data:www-data .
chmod -R 775 storage bootstrap/cache

# 6. Restart services
systemctl restart php8.1-fpm
systemctl restart nginx