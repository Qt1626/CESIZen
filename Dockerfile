FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock symfony.lock ./

RUN composer install \
    --no-interaction \
    --prefer-dist \
    --no-scripts

COPY . .

# Fichier minimal attendu par Symfony.
# Aucun secret n'est stocké dans l'image.
RUN printf "APP_ENV=prod\nAPP_DEBUG=0\n" > /app/.env

CMD ["sh", "-c", "php bin/console doctrine:schema:update --force --env=prod && php -S 0.0.0.0:${PORT:-10000} -t public"]