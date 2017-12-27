FROM php:7.1-apache

LABEL maintainer "josef.moravec@gmail.com"

RUN apt-get update -y && \
    apt-get install -y --no-install-recommends apt-utils git libicu-dev zlib1g-dev && \
    apt-get clean -y && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install intl json mbstring mysqli pdo pdo_mysql zip sockets

RUN a2enmod rewrite && \
    sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-enabled/000-default.conf && \
    mv /var/www/html /var/www/public

RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./

RUN composer install --no-interaction --no-dev

COPY . ./

RUN chown -R www-data:www-data /var/www/
