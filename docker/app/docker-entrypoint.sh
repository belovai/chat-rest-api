#!/bin/bash
set -e

php -v
### Check if a vendor installed ###
if [ ! -d "/var/www/vendor" ]
then
    rm -f ./bootstrap/cache/*.php
    su - -c "composer install" www-data
    su - -c "composer clear-cache" www-data
fi
php artisan -V --no-ansi
exec /usr/bin/supervisord -n -c /var/www/docker/app/supervisord.conf
exit

exec "$@"
