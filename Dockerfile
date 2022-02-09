FROM php:7.4-fpm

WORKDIR /var/www/
COPY ./src /var/www/
RUN  docker-php-ext-install pdo pdo_mysql

CMD ["php-fpm"]