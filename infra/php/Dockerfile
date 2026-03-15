FROM php:8.3.14-fpm-alpine

RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    fcgi \
    gettext \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) mysqli pdo_mysql gd intl zip opcache \
    && docker-php-ext-enable opcache

WORKDIR /var/www/html

COPY php.ini.template /usr/local/etc/php/php.ini.template
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

