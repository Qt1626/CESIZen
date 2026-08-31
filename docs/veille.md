# Méthode de veille technologique et sécurité - CESIZen

## 1. Objectif

La veille permet de suivre les évolutions techniques et les vulnérabilités pouvant avoir un impact sur l'application CESIZen.

Elle concerne principalement :

- Symfony ;
- PHP ;
- PostgreSQL ;
- Docker ;
- GitHub Actions ;
- les dépendances Composer ;
- Render ;
- les bonnes pratiques de cybersécurité.

## 2. Sources utilisées

Les principales sources consultées sont :

- documentation officielle Symfony ;
- Symfony Security Advisories ;
- documentation PHP ;
- documentation PostgreSQL ;
- documentation Docker ;
- documentation GitHub Actions ;
- alertes et avis de sécurité GitHub ;
- Composer Audit ;
- ANSSI ;
- documentation Render.

Les sources officielles sont privilégiées afin de limiter les informations incorrectes ou obsolètes.

## 3. Fréquence de la veille

La veille peut être réalisée :

- une fois par semaine pour les évolutions techniques ;
- lors de chaque mise à jour importante du projet ;
- immédiatement lorsqu'une alerte de sécurité critique est publiée.

## 4. Méthode

La méthode de veille suivie est la suivante :

1. consulter les sources sélectionnées ;
2. identifier les nouveautés ou vulnérabilités concernant les technologies utilisées ;
3. évaluer leur impact potentiel sur CESIZen ;
4. créer un ticket GitHub lorsqu'une action est nécessaire ;
5. attribuer une priorité au ticket ;
6. appliquer la correction sur une branche dédiée ;
7. valider la correction avec la CI ;
8. documenter la modification.

## 5. Exemple appliqué au projet

Une analyse des dépendances Composer a permis d'identifier plusieurs vulnérabilités dans les dépendances du projet.

Les dépendances Symfony et associées ont été mises à jour.

La commande :

```bash
composer audit