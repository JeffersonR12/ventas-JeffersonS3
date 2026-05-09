#!/bin/bash
# Ajustar permisos para el directorio montado
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

# Iniciar Apache
apache2-foreground