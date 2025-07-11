FROM php:8.3.14-apache

ENV APACHE_DOCUMENT_ROOT=/workspace/blogsymfony/public

RUN apt-get update \
    && apt-get install -y git zip unzip libzip-dev openssl \
    && docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /workspace/blogsymfony

EXPOSE 8000

CMD ["apache2-foreground"]
