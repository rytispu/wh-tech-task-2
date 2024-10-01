FROM composer AS vendor
WORKDIR /app
COPY composer.json composer.lock /app/

FROM php:8.1.17-apache AS base

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip

WORKDIR /var/www/html

RUN docker-php-ext-install pdo_mysql && \
    a2enmod allowmethods rewrite

COPY docker/vhost.conf /etc/apache2/sites-enabled/000-default.conf
COPY docker/ports.conf /etc/apache2/ports.conf
COPY --from=composer /usr/bin/composer /usr/bin/composer
