FROM php:8.3-fpm

RUN apt-get update && apt-get install -y libpq-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

RUN docker-php-ext-install pdo pdo_pgsql pgsql

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

#RUN docker-php-ext-install pgsql
#RUN apt-get install -y libpq-dev && \
#    docker-php-ext-install pdo pdo_pgsql pgsql

WORKDIR /var/www/html