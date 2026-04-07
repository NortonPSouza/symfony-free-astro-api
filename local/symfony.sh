#!/bin/bash
set -e

if [ ! -d "/var/www/html/vendor" ]; then
    composer install
fi

exec frankenphp run --config /var/www/html/Caddyfile
