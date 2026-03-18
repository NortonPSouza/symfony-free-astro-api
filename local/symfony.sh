#!/bin/bash
if [ ! -d "/var/www/code/vendor" ] ; then
  composer install
fi
php -S 0.0.0.0:8000 -t public/
