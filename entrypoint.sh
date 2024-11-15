#!/bin/bash
chown -R application:application /app/storage
php /app/artisan optimize
php /app/artisan migrate --force
