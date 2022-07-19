FROM php:8.1-fpm-alpine as base
WORKDIR /app

RUN apk upgrade --no-cache && \
    apk add --no-cache icu-dev gmp gmp-dev nginx && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-configure intl && \
    docker-php-ext-install gmp pdo pdo_mysql opcache bcmath intl

COPY build/package/php/php.ini $PHP_INI_DIR/php.ini
COPY build/package/php/php-fpm.conf /usr/local/etc/php-fpm.d/zzz-php-fpm.conf

RUN adduser -D -u 1000 -g appuser appuser
RUN chown -R appuser:appuser /app
USER appuser

FROM base as development

USER root

COPY --from=composer:2.3.9 --chown=appuser /usr/bin/composer /usr/local/bin/
RUN apk add --no-cache make

USER appuser
