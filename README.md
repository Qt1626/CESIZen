# CESIZen

Application web de santé mentale permettant à un utilisateur de suivre des
exercices de respiration et de consulter des contenus informatifs.

Projet réalisé dans le cadre du titre **Concepteur Développeur d'Applications
(CDA)** au CESI — Bloc 3, Activité 3 *« Déployer et sécuriser les applications
informatiques »*. Le dossier complet (plan de déploiement, maintenance,
sécurisation) se trouve dans `Claude outputs/CESIZen_Activite3_Deploiement_Securisation-4.docx`
et s'appuie sur la documentation détaillée du dossier [`docs/`](docs).

## Stack technique

- **Backend** : Symfony 7.4 / PHP 8.2, API Platform
- **Base de données** : PostgreSQL 16, migrations Doctrine
- **Back-office** : EasyAdmin
- **Conteneurisation** : Docker (build multi-étapes : `base`, `dev`, `qa`, `prod`)
- **CI/CD** : GitHub Actions, image publiée sur GitHub Container Registry, déploiement sur [Render](https://render.com)
- **Sécurité** : Gitleaks, `composer audit`, Trivy, OWASP ZAP, SonarCloud (voir [`docs/security-plan.md`](docs/security-plan.md))

## Architecture des environnements

Le projet est organisé en cascade de 4 environnements, chacun avec sa propre
base PostgreSQL isolée (voir `compose.yaml`) :

| Environnement | Cible Dockerfile | Données | Mise à jour de schéma |
|---|---|---|---|
| **dev** | `dev` | Jetables (fixtures) | `doctrine:schema:update --force` |
| **qa / test** | `qa` | Réinitialisables | Migrations Doctrine versionnées |
| **préproduction** | `prod` | Jamais réinitialisées | Migrations Doctrine versionnées |
| **production** | `prod` | Jamais réinitialisées | Migrations Doctrine versionnées |

Le détail complet (schéma de la chaîne CI/CD, plan de déploiement par
environnement, stratégie de versioning) est décrit dans le dossier
Activité 3 et dans [`docs/rollback-test.md`](docs/rollback-test.md).

## Démarrage local (environnement dev)

Prérequis : Docker et Docker Compose.

Le fichier `.env.dev.local` (non versionné, voir `.gitignore`) doit exister à
la racine du projet avec les identifiants de la base PostgreSQL locale ; il
est déjà présent dans un environnement de développement déjà initialisé.

```bash
# Construire et démarrer l'environnement de développement
docker compose up --build app-dev db-dev
```

L'application est alors accessible sur <http://localhost:8001>.

Un utilisateur administrateur et des données de démonstration peuvent être
chargés via les fixtures :

```bash
docker exec cesizen-dev php bin/console doctrine:fixtures:load --no-interaction
```

L'environnement dev est reconstruit automatiquement chaque nuit (voir
`.github/workflows/nightly-dev-rebuild.yml`) pour repartir d'un état propre.

### Autres environnements

```bash
docker compose up --build app-qa db-qa            # QA / test (port 8002)
docker compose up --build app-preprod db-preprod  # Préproduction (port 8003)
docker compose up --build app-prod db-prod        # Production (port 8004)
```

Ces environnements appliquent uniquement des migrations Doctrine
explicitement versionnées (jamais de réinitialisation de schéma), voir
[`docs/rollback-test.md`](docs/rollback-test.md).

## Lancer les tests

```bash
composer install
cp .env.test .env
php bin/console doctrine:migrations:migrate --no-interaction --env=test
vendor/bin/phpunit
```

Ces mêmes étapes (+ lint YAML/Twig, audit de sécurité Composer, scan Trivy de
l'image Docker, analyse SonarCloud) sont exécutées automatiquement à chaque
push et Pull Request par le workflow [`ci.yml`](.github/workflows/ci.yml).

## Intégration et déploiement continus

- [`ci.yml`](.github/workflows/ci.yml) : tests, qualité de code, sécurité (bloquant)
- [`cd.yml`](.github/workflows/cd.yml) : build + publication de l'image Docker, déploiement automatique sur Render, scan OWASP ZAP de l'environnement QA
- [`nightly-dev-rebuild.yml`](.github/workflows/nightly-dev-rebuild.yml) : reconstruction nocturne de l'environnement dev + test de restauration de sauvegarde
- [`uptime-check.yml`](.github/workflows/uptime-check.yml) : supervision de disponibilité (toutes les 15 min)
- [`auto-ticket.yml`](.github/workflows/auto-ticket.yml) : ouverture automatique d'un ticket GitHub en cas d'échec de la CI, de la CD ou de la reconstruction nocturne

## Gestion des évolutions et des anomalies

Le suivi des tickets se fait via **GitHub Issues** (labels type/priorité/statut).
La méthodologie complète (cycle prestataire ↔ client) et le SLA associé sont
décrits dans [`docs/ticketing.md`](docs/ticketing.md) et [`docs/sla.md`](docs/sla.md).

## Sécurité et RGPD

- Analyse des risques, criticité et actions correctives : [`docs/security-plan.md`](docs/security-plan.md)
- Procédure de gestion d'incident / crise : [`docs/incident-response.md`](docs/incident-response.md)
- Protection des données personnelles et données de santé (art. 9 RGPD) : [`docs/rgpd.md`](docs/rgpd.md)
- Veille technologique : [`docs/veille.md`](docs/veille.md)

## Structure du projet

```
src/
  Controller/       Contrôleurs (inscription, profil, exercices, API...)
  Entity/           Entités Doctrine (Utilisateur, InfoUtilisateur, ExerciceRespiration...)
  Form/             Formulaires Symfony
  Repository/       Repositories Doctrine
  EventSubscriber/  Souscripteurs (en-têtes de sécurité, redirection HTTPS...)
  Security/         Configuration liée à l'authentification
templates/          Vues Twig
migrations/         Migrations Doctrine versionnées (historique dans docs/legacy-migrations)
docs/               Documentation Activité 3 (déploiement, maintenance, sécurité, RGPD)
.github/workflows/  Pipelines CI/CD GitHub Actions
compose.yaml        Définition des 4 environnements (dev/qa/preprod/prod)
Dockerfile          Build multi-étapes (base, dev, qa, prod)
```

## Stratégie de versioning Git

- `master` : branche protégée, fusion soumise à Pull Request et succès de la CI
- `develop` : palier intermédiaire pour le travail courant avant intégration à `master`
- Branches thématiques : `feature/*`, `fix/*`, `security/*`, `docs/*`, `architecture/*`, `chore/*`
- Images Docker taguées `latest` et par hash de commit (`ghcr.io/qt1626/cesizen:<sha>`) pour une traçabilité exacte et un retour arrière immédiat

## Licence

Projet privé (`proprietary`), réalisé dans un cadre pédagogique (CESI, titre CDA).
