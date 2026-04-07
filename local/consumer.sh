#!/bin/bash
set -e

if [ ! -d "/var/www/html/vendor" ]; then
    composer install
fi

exec php bin/console app:consumer:report
