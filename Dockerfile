FROM php:8.2-fpm


RUN apt-get update && apt-get install -y \
    zip unzip curl git libzip-dev libpng-dev libonig-dev libxml2-dev libpq-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd

    COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
