FROM php:8.2-cli

# Installation des dépendances système et de PostgreSQL
RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Masque la version de PHP dans les réponses HTTP
RUN echo "expose_php = Off" > /usr/local/etc/php/conf.d/security.ini

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copie des fichiers Composer
COPY composer.json composer.lock symfony.lock ./

# Installation des dépendances PHP
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --no-scripts

# Copie du projet
COPY . .

# Fichier .env minimal pour Symfony.
# Les vraies valeurs sensibles viennent de Render.
RUN printf "APP_ENV=prod\nAPP_DEBUG=0\n" > /app/.env

EXPOSE 10000

# Initialisation du schéma puis démarrage de Symfony
CMD ["sh", "-c", "php bin/console doctrine:schema:update --force --env=prod && php -S 0.0.0.0:${PORT:-10000} -t public"]