FROM php:7.4-apache
# Instalacja modułu mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Kopiowanie plików aplikacji do katalogu docelowego w kontenerze
COPY . /var/www/html

# Ustawienie uprawnień
RUN chown -R www-data:www-data /var/www/html

# Rozpoczęcie serwera Apache
CMD ["apache2-foreground"]