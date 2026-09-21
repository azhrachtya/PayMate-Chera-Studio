FROM tangramor/nginx-php8-fpm:php8.3.6_node22.1.0

COPY . /var/www/html

ENV WEBROOT="/var/www/html/public"
ENV CREATE_LARAVEL_STORAGE="1"

RUN cd /var/www/html \
    && composer install --no-dev --optimize-autoloader \
    && npm ci && npm run build \
    && chown -Rf nginx.nginx /var/www/html