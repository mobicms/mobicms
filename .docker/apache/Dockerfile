FROM httpd:2.4-alpine

# Получаем USER_ID и GROUP_ID из docker compose
ARG USER_ID=1000
ARG GROUP_ID=1000

# Обновление системы
RUN apk update && apk upgrade

# Назначаем USER_ID / GROUP_ID
RUN apk add --no-cache shadow \
    && groupmod -o -g ${GROUP_ID} www-data \
    && usermod -o -u ${USER_ID} -g www-data www-data

# Создаем папку для LOG файлов и назначаем права доступа
RUN mkdir -p /app/logs/apache \
    && chown -R www-data:www-data /app/logs/apache \
    && chown -R www-data:www-data /usr/local/apache2/logs

# Копируем конфигурационные файлы
COPY config/httpd.conf /usr/local/apache2/conf/
COPY config/httpd-vhosts.conf /usr/local/apache2/conf/extra/

# Копируем SSL сертификаты
COPY ssl/localhost.crt /usr/local/apache2/conf/server.crt
COPY ssl/localhost.key /usr/local/apache2/conf/server.key

USER www-data

CMD ["httpd-foreground"]

WORKDIR /app

EXPOSE 80 443
