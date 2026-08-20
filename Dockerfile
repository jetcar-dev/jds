# syntax=docker/dockerfile:1.7

FROM node:22-alpine AS workspace
WORKDIR /build

COPY package.json package-lock.json turbo.json ./
COPY apps/docs/package.json ./apps/docs/package.json
COPY packages/jds/package.json ./packages/jds/package.json
RUN npm ci

COPY apps/docs ./apps/docs
COPY packages/jds ./packages/jds

ENV BLADE_PREVIEW_ORIGIN=http://preview
RUN npm run build --workspace=@jetcar/jds \
    && npm run build --workspace=@jetcar/docs

FROM node:22-alpine AS docs-runtime
ENV NODE_ENV=production \
    HOSTNAME=0.0.0.0 \
    PORT=3000
WORKDIR /app

COPY --from=workspace /build/apps/docs/.next/standalone ./
COPY --from=workspace /build/apps/docs/.next/static ./apps/docs/.next/static
COPY --from=workspace /build/apps/docs/public ./apps/docs/public

USER node
EXPOSE 3000
HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD node -e "fetch('http://127.0.0.1:3000/up').then(r=>process.exit(r.ok?0:1)).catch(()=>process.exit(1))"
CMD ["node", "apps/docs/server.js"]

FROM composer:2 AS preview-vendor
WORKDIR /build
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY apps/docs/blade ./apps/docs/blade
RUN cd apps/docs/blade && composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader

FROM php:8.3-apache-bookworm AS preview-runtime
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
COPY --from=preview-vendor /build/apps/docs/blade ./
COPY --from=workspace /build/apps/docs/content /var/www/content
COPY --from=workspace /build/packages/jds /packages/jds
COPY deploy/docker-entrypoint.sh /usr/local/bin/jds-entrypoint

RUN chmod +x /usr/local/bin/jds-entrypoint \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD php -r "exit(@file_get_contents('http://127.0.0.1/up') === false ? 1 : 0);"

ENTRYPOINT ["jds-entrypoint"]
CMD ["apache2-foreground"]
