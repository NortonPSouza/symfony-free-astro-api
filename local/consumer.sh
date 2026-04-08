#!/bin/bash
set -e

if [ ! -d "/var/www/html/vendor" ]; then
    composer install
fi

php bin/console cache:warmup

exec php bin/console app:consumer:report
