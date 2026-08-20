# syntax=docker/dockerfile:1.7

FROM node:22-alpine AS assets
WORKDIR /build

COPY packages/jds/package.json packages/jds/package-lock.json ./packages/jds/
RUN cd packages/jds && npm ci
COPY packages/jds ./packages/jds
RUN cd packages/jds && npm run build && rm -rf node_modules

FROM composer:2 AS vendor
WORKDIR /build
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_MIRROR_PATH_REPOS=1

COPY apps/docs/blade ./apps/docs/blade
COPY apps/docs/content ./apps/docs/content
COPY --from=assets /build/packages/jds ./packages/jds
RUN cd apps/docs/blade && composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader

FROM php:8.3-apache-bookworm AS runtime
ENV APP_ENV=production \
    APP_DEBUG=false \
    APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN apt-get update \
    && apt-get install -y --no-install-recommends libicu-dev libonig-dev \
    && docker-php-ext-install intl mbstring opcache \
    && a2enmod rewrite headers expires \
    && rm -rf /var/lib/apt/lists/*

COPY deploy/apache.conf /etc/apache2/conf-available/jds.conf
RUN a2enconf jds \
    && sed -ri "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf

WORKDIR /var/www/html
COPY --from=vendor /build/apps/docs/blade ./
COPY --from=vendor /build/apps/docs/content /var/www/content
COPY --from=assets /build/packages/jds /packages/jds
COPY deploy/docker-entrypoint.sh /usr/local/bin/jds-entrypoint

RUN chmod +x /usr/local/bin/jds-entrypoint \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD php -r "exit(@file_get_contents('http://127.0.0.1/up') === false ? 1 : 0);"

ENTRYPOINT ["jds-entrypoint"]
CMD ["apache2-foreground"]
