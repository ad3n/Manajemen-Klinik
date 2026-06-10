FROM node:alpine3.22 AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run build

FROM php:8.4-fpm-alpine

# System dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    unzip \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev

# PHP Extensions
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg && \
    docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    mbstring \
    zip \
    intl \
    gd \
    opcache

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install dependency terlebih dahulu agar cache layer optimal
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

# Copy source code
COPY . .

# Copy hasil build frontend
COPY --from=frontend /app/public/build ./public/build

# Laravel optimization
RUN composer dump-autoload --optimize

RUN php artisan config:cache || true && \
    php artisan route:cache || true && \
    php artisan view:cache || true

# Permission
RUN mkdir -p storage/logs bootstrap/cache && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# Nginx config
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Supervisor
COPY docker/supervisord.conf /etc/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord","-c","/etc/supervisord.conf"]
