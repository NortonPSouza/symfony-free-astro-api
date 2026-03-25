#!/bin/bash
if [ ! -d "/var/www/html/vendor" ] ; then
  composer install
fi

php bin/console app:consumer:report &

php -S 0.0.0.0:8000 -t public/
