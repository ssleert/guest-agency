FROM php:8.4.0RC4-zts-alpine3.20

RUN docker-php-ext-install pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl
