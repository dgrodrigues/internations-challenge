#!/bin/bash

set -e

sleep 5
php /usr/bin/composer install

exec "$@"

tail -f /dev/null;