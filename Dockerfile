FROM php:8.1-apache

# Install mysqli PHP extension for MySQL connectivity
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache mod_rewrite module
RUN a2enmod rewrite

# Copy project source files to webserver root directory
COPY . /var/www/html/

# Expose HTTP port
EXPOSE 80
