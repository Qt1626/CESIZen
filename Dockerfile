FROM php:8.2-cli

# Dépendances système nécessaires à Symfony/PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Installation des dépendances PHP
COPY composer.json composer.lock symfony.lock ./
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --no-scripts

# Copie du projet
COPY . .

# Installation finale et scripts Symfony

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]