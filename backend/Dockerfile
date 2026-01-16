FROM php:8.3-apache

# Dependencias...
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libjpeg-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_mysql zip exif pcntl gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# IMPORTANTE: Ahora el apache.conf está en la misma carpeta que el Dockerfile
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

WORKDIR /var/www/html

# Si vas a usar Laravel como API/Backend, no necesitas instalar node aquí, 
# ya que lo tienes en un contenedor aparte.