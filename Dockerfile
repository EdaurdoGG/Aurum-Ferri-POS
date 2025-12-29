FROM php:7.4-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli zip \
    && docker-php-ext-enable gd mysqli zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Activar mod_rewrite (no rompe nada)
RUN a2enmod rewrite

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html
