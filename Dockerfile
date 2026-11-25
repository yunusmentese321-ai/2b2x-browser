FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends ca-certificates \
    && rm -rf /var/lib/apt/lists/* \
    && a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork rewrite headers

COPY . /var/www/html/

RUN mkdir -p /var/www/html/uploads/browsers /var/www/html/uploads/blog /var/www/html/data \
    && chown -R www-data:www-data /var/www/html/data /var/www/html/uploads \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/data /var/www/html/uploads

EXPOSE 10000

CMD ["sh", "-c", "PORT=${PORT:-10000}; sed -i \"s/Listen 80/Listen $PORT/g\" /etc/apache2/ports.conf; sed -i \"s/:80/:$PORT/g\" /etc/apache2/sites-available/000-default.conf; exec apache2-foreground"]
