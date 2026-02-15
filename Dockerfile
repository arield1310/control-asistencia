FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_pgsql pgsql

COPY control-asistencia/ /var/www/html/

EXPOSE 80
