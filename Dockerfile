FROM php:8.1-apache

COPY . /var/www/html

RUN apt-get update && apt-get install -y libpq-dev zip unzip && docker-php-ext-install pdo pdo_pgsql && a2enmod rewrite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN cd /var/www/html && composer install --no-interaction --no-progress --no-suggest

EXPOSE 80