FROM php:8.2-cli AS base

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Masque la version PHP
RUN echo "expose_php = Off" > /usr/local/etc/php/conf.d/security.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock symfony.lock ./


# =========================================================
# DEV
# =========================================================
FROM base AS dev

# DEV contient les dépendances de développement
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --no-scripts

COPY . .

RUN printf "APP_ENV=dev\nAPP_DEBUG=1\n" > /app/.env

EXPOSE 10000

CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]


# =========================================================
# QA
# =========================================================
FROM base AS qa

# QA contient PHPUnit et les outils de test
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --no-scripts

COPY . .

RUN printf "APP_ENV=test\nAPP_DEBUG=0\n" > /app/.env

EXPOSE 10000

CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]


# =========================================================
# PREPROD / PROD
# =========================================================
FROM base AS prod

# Pas de dépendances de développement
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --optimize-autoloader

COPY . .

# Les tests ne sont pas nécessaires dans l'image de production
RUN rm -rf /app/tests \
    && printf "APP_ENV=prod\nAPP_DEBUG=0\n" > /app/.env

EXPOSE 10000

# On conserve le comportement actuellement utilisé par Render
CMD ["sh", "-c", "php bin/console doctrine:schema:update --force --env=prod && php -S 0.0.0.0:${PORT:-10000} -t public"]