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

# Utilisateur non privilegie : l'application ne doit jamais tourner en root
# (voir docs/security-plan.md, risque "conteneur applicatif execute en root").
RUN useradd --create-home --uid 1000 --shell /bin/bash appuser

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

RUN printf "APP_ENV=dev\nAPP_DEBUG=1\n" > /app/.env \
    && chown -R appuser:appuser /app

USER appuser

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

RUN printf "APP_ENV=test\nAPP_DEBUG=0\n" > /app/.env \
    && chown -R appuser:appuser /app

USER appuser

EXPOSE 10000

# Seules les migrations versionnees sont appliquees (jamais de reset de schema),
# y compris en QA : voir docs/rollback-test.md et docs/security-plan.md.
CMD ["sh", "-c", "php bin/console doctrine:migrations:migrate --no-interaction --env=test && php -S 0.0.0.0:10000 -t public"]


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
    && printf "APP_ENV=prod\nAPP_DEBUG=0\n" > /app/.env \
    && chown -R appuser:appuser /app

USER appuser

EXPOSE 10000

# doctrine:migrations:migrate remplace l'ancien doctrine:schema:update --force :
# seules les migrations explicitement versionnees et testees (voir
# docs/rollback-test.md) sont appliquees, ce qui garantit qu'aucune donnee de
# preproduction/production n'est jamais reinitialisee ni modifiee de maniere
# non maitrisee.
#
# ETAPE TEMPORAIRE (a retirer apres le premier deploiement reussi) : la base
# de production existait deja (creee via schema:update) avant le passage aux
# migrations. On indique donc a Doctrine que la migration de baseline
# Version20260901083316 est deja appliquee (aucun SQL rejoue, juste un
# enregistrement), avant de laisser migrate appliquer la suite normalement.
CMD ["sh", "-c", "php bin/console doctrine:migrations:version --add --no-interaction 'DoctrineMigrations\\Version20260901083316' || true; php bin/console doctrine:migrations:migrate --no-interaction --env=prod && php -S 0.0.0.0:${PORT:-10000} -t public"]
