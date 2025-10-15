FROM php:8.4-fpm-alpine

# Получаем USER_ID и GROUP_ID из docker compose
ARG USER_ID=1000
ARG GROUP_ID=1000

# Обновление системы
RUN apk update && apk upgrade

# Назначаем USER_ID / GROUP_ID
RUN apk add --no-cache shadow \
    && groupmod -o -g ${GROUP_ID} www-data \
    && usermod -o -u ${USER_ID} -g www-data www-data

# Установка необходимых библиотек
RUN apk add --no-cache \
    libpng-dev \
    libwebp-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    curl-dev \
    unzip \
    zip

# Установка необходимых расширений
RUN docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql mysqli curl opcache \
    && docker-php-ext-enable gd pdo pdo_mysql mysqli curl opcache

# Создаем папку для LOG файлов
RUN mkdir -p /app/logs/php

# Копируем конфигурационные файлы
COPY config/php.ini "$PHP_INI_DIR/conf.d/"
COPY config/sendmail.ini "$PHP_INI_DIR/conf.d/"

WORKDIR /app
