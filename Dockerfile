FROM php:8.1-apache

# Instalar extensiones necesarias para MySQL y otras dependencias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip

# Copiar los archivos del proyecto al contenedor
COPY . /var/www/html

# Cambiar propietario de los archivos a www-data
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80
EXPOSE 80