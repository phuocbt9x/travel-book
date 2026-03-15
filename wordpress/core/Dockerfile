FROM alpine:3.23.2

RUN apk add --no-cache \
    curl \
    tar \
    libwebp \
    php83-cli \
    php83-fpm \
    php83-phar \
    php83-json \
    php83-mbstring \
    php83-openssl \
    php83-curl \
    php83-dom \
    php83-xml \
    php83-xmlreader \
    php83-simplexml \
    php83-gd \
    php83-zip \
    php83-mysqli \
    php83-pdo \
    php83-pdo_mysql \
    php83-intl \
    php83-fileinfo \
    php83-iconv \
    php83-exif \
    php83-opcache \
    php83-tokenizer \
    php83-ctype \
    php83-session

RUN ln -sf /usr/bin/php83 /usr/bin/php

RUN addgroup -g 82 -S www-data 2>/dev/null || true \
    && adduser -u 82 -D -S -G www-data www-data 2>/dev/null || true

WORKDIR /usr/src

COPY core/wordpress-6.9.4.tar.gz /tmp/
RUN tar -xzf /tmp/wordpress-6.9.4.tar.gz \
    && rm /tmp/wordpress-6.9.4.tar.gz

COPY wp-content/ /usr/src/wp-content/

RUN mkdir -p /var/www/html

COPY core/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]