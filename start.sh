#!/bin/bash

# Եթե PORT-ը սահմանված չէ, լռելյայն դնել 80
PORT=${PORT:-80}

# nginx.conf-ում PORT_NUMBER-ը փոխարինել իրական PORT-ով
sed -i "s/PORT_NUMBER/$PORT/g" /etc/nginx/sites-available/default 2>/dev/null || sed -i "s/PORT_NUMBER/$PORT/g" /etc/nginx/conf.d/default.conf 2>/dev/null

# Միացնել PHP-FPM-ը
php-fpm -D

# Միացնել Nginx-ը
nginx -g 'daemon off;'