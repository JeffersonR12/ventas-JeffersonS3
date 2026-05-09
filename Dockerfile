FROM php:8.1-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql zip mysqli # <--- Agregamos mysqli aquí

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Permitir que el archivo .htaccess funcione (cambiar AllowOverride None a All)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copiar los archivos del proyecto
COPY . /var/www/html

# Copiar el script de entrada
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Exponer puerto 80
EXPOSE 80

# Comando para iniciar Apache
CMD ["/usr/local/bin/docker-entrypoint.sh"]