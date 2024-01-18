ARG PHP_IMAGE_TAG
FROM php:${PHP_IMAGE_TAG}-cli-alpine

RUN apk add autoconf gcc g++ make
RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /opt/test
