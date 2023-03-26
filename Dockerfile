FROM php:8.2

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.* ./

RUN composer install --no-scripts --no-autoloader --prefer-dist --no-progress

COPY . .
