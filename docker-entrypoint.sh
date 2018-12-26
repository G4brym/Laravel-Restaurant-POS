#!/bin/sh
forever start /var/www/server.js

set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

exec "$@"

php-fpm
