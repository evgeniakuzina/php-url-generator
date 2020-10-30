#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-app}

if [ "$role" = "app" ]; then

    exec php-fpm

elif [ "$role" = "queue" ]; then

    echo "Running the queue..."
    php /var/www/artisan queue:work --verbose --tries=3 --timeout=90

elif [ "$role" = "scheduler" ]; then

    while [ true ]
    do
      php /var/www/artisan schedule:run --verbose --no-interaction &
      sleep 60
    done

fi
