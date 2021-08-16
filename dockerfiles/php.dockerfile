FROM php:8.0-fpm-alpine


WORKDIR /var/www/html/  

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
&& pecl install xdebug \
&& docker-php-ext-enable xdebug  

RUN apk update && apk add postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql

