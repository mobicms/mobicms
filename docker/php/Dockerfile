FROM php:8.4-fpm-alpine3.22

# Получаем USER_ID и GROUP_ID из docker compose
ARG USER_ID=1000
ARG GROUP_ID=1000

# Установка необходимых библиотек
RUN apk update && apk add --no-cache \
    libpng-dev \
    libwebp-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    curl-dev \
    shadow \
    unzip \
    zip \
    msmtp

# Установка необходимых расширений
RUN docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql opcache \
    && docker-php-ext-enable gd pdo pdo_mysql opcache

# Копируем конфигурационные файлы
COPY php.ini "$PHP_INI_DIR/conf.d/"
COPY sendmail.ini "$PHP_INI_DIR/conf.d/"
COPY msmtprc /etc/msmtprc

# Создаём пользователя с нужным UID/GID
RUN groupmod -o -g ${GROUP_ID} www-data && \
    usermod -o -u ${USER_ID} -g www-data www-data

WORKDIR /app
