# Use the official PHP 8.1 CLI image as the base
FROM php:8.3-cli

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install Xdebug via PECL
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configure Xdebug
RUN printf "\
zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)\n\
xdebug.mode=debug\n\
xdebug.start_with_request=yes\n\
xdebug.client_host=host.docker.internal\n\
xdebug.client_port=9003\n\
xdebug.log=/tmp/xdebug.log\n\
" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files to the container
COPY . /app

# Ensure entrypoint.sh is executable
RUN chmod +x /app/entrypoint.sh

# Expose Xdebug port (optional, since Xdebug initiates the connection)
EXPOSE 9003

# Set the entrypoint script
ENTRYPOINT ["/app/entrypoint.sh"]

# Default command to keep the container running
CMD ["tail", "-f", "/dev/null"]
