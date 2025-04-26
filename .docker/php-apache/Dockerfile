FROM php:8.3-apache

RUN apt-get update
RUN apt-get install -y unzip libfreetype6-dev libjpeg62-turbo-dev libpng-dev libzip-dev libcurl3-dev libwebp-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql \
    && docker-php-ext-enable gd pdo_mysql \
    && a2enmod rewrite
