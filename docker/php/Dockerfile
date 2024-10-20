FROM php:8.0.0-fpm

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN docker-php-ext-install mysqli
