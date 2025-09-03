FROM httpd:2.4-alpine3.22

COPY httpd.conf /usr/local/apache2/conf/
COPY httpd-vhosts.conf /usr/local/apache2/conf/extra/

# Копируем в контейнер SSL сертификаты
COPY ssl/localhost.crt /usr/local/apache2/conf/server.crt
COPY ssl/localhost.key /usr/local/apache2/conf/server.key

EXPOSE 80 443

CMD ["httpd-foreground"]
