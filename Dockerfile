# Dockerfile for PHP-Apache Web Service
FROM php:7.4-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy application files to the container's web root
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Start Apache server
CMD ["apache2-foreground"]
