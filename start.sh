#!/bin/bash
php-fpm -D
php artisan config:cache
php artisan route:cache
nginx -g "daemon off;"
