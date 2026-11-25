FROM php:8.2-cli

WORKDIR /var/www/html

COPY . /var/www/html/

RUN mkdir -p /var/www/html/uploads/browsers /var/www/html/uploads/blog /var/www/html/data \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/data /var/www/html/uploads

EXPOSE 10000

ENV PHP_CLI_SERVER_WORKERS=4

CMD ["sh", "-c", "exec php -S 0.0.0.0:${PORT:-10000} -t /var/www/html"]
