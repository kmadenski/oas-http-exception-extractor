#!/bin/sh
set -e

# Configure Git to consider /app as a safe directory
git config --global --add safe.directory /app

# Run composer install
composer install --no-interaction --prefer-dist --optimize-autoloader

# Execute the passed command to keep the container running
exec "$@"