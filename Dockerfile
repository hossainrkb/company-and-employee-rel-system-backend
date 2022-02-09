FROM php:8.0-fpm

WORKDIR /var/www/
COPY ./src /var/www/
RUN  docker-php-ext-install pdo pdo_mysql

CMD ["php-fpm"]