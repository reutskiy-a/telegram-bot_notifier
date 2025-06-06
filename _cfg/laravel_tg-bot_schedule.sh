#!/bin/bash
cd /var/www/tg-bot.reutskiy-a.ru
docker compose exec -T php-fpm php artisan schedule:run