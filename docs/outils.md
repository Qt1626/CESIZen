# Outils du projet — rôle, configuration, comment les montrer

Ce document recense chaque outil utilisé dans le projet CESIZen : à quoi il
sert, où il est configuré dans le dépôt, et comment le montrer concrètement
à l'oral (commande à taper ou écran à ouvrir). Il sert de fiche de révision
avant la soutenance et de support pour justifier chaque brique face au jury.

## Versioning et hébergement du code

### Git / GitHub

- **Rôle** : gestion du code source et des branches.
- **Configuré** : tout le dépôt ; flux décrit dans le README (`master`
  protégée, `develop`, branches `feature/`, `fix/`, `security/`, `docs/`).
- **Comment le montrer** : onglet **Branches** du dépôt, ou en local
  `git branch -a` et `git log --oneline --graph -15`.
- **Pourquoi** : répond au critère « outil de versioning pertinent configuré
  et performant ».

### GitHub Actions

- **Rôle** : orchestrateur qui exécute automatiquement tous les autres
  outils (CI, CD, tickets, supervision) à chaque événement du dépôt.
- **Configuré** : `.github/workflows/*.yml` (5 fichiers).
- **Comment le montrer** : onglet **Actions** du dépôt — historique des
  runs, durée, logs détaillés par étape.
- **Pourquoi** : c'est le cœur du « plan de déploiement structuré » et du
  « pilotage du déploiement ».

### GitHub Container Registry (GHCR)

- **Rôle** : registre où chaque image Docker construite par la CD est
  publiée, taguée `latest` et par hash de commit.
- **Configuré** : étape « Construire et publier l'image Docker » de
  `cd.yml` ; visible sur `github.com/Qt1626/CESIZen/pkgs/container/cesizen`.
- **Comment le montrer** : ouvrir la page **Packages** du profil GitHub ou
  du dépôt, montrer les deux tags (`latest` et le SHA) sur la même image.
- **Pourquoi** : illustre la traçabilité exacte code ↔ image ↔ déploiement.

### Render

- **Rôle** : plateforme d'hébergement (PaaS) qui exécute l'image Docker en
  environnement QA, redéployée automatiquement via un webhook.
- **Configuré** : secret `RENDER_DEPLOY_HOOK_URL`, appelé dans `cd.yml`.
- **Comment le montrer** : tableau de bord Render → service `cesizen-qa` →
  onglet **Events**, puis ouvrir l'URL publique de l'environnement.
- **Pourquoi** : seul l'environnement réellement automatisé de bout en bout
  aujourd'hui ; à assumer clairement à l'oral (voir dossier, section 1.1).

## Maintenance et ticketing

### GitHub Issues (+ labels)

- **Rôle** : outil de gestion des évolutions et anomalies, avec labels
  type/priorité/statut.
- **Configuré** : labels créés manuellement sur le dépôt ; alimentés
  automatiquement par `auto-ticket.yml` et `uptime-check.yml`.
- **Comment le montrer** : onglet **Issues**, filtrer par label
  `auto:ci-cesizen` ou `security` pour distinguer les tickets automatiques
  des tickets manuels.
- **Pourquoi** : répond directement au critère ticketing/évolutions.

### GitHub Projects (tableau de suivi)

- **Rôle** : vue Kanban (à faire / en cours / en revue / terminé) pour
  piloter visuellement les tickets.
- **Comment le montrer** : onglet **Projects** du dépôt.
- **Pourquoi** : donne une preuve visuelle du pilotage évoqué en 1.8 et 2.1
  du dossier ; à activer avant l'oral s'il ne l'est pas encore.

## Sécurité (chaîne d'outils CI)

### Gitleaks

- **Rôle** : scan du code à la recherche de secrets (mots de passe, clés
  API) accidentellement commités ; bloquant.
- **Configuré** : étape « Scanner les secrets avec Gitleaks » dans
  `ci.yml`.
- **Comment le montrer** : logs de cette étape dans un run CI vert (« no
  leaks found »), ou expliquer qu'un secret détecté ferait échouer la CI
  immédiatement, avant même l'installation des dépendances.
- **Pourquoi** : couvre le risque n°1 du plan de sécurisation (fuite de
  secrets, criticité élevée).

### Composer Audit

- **Rôle** : vérifie les dépendances PHP (`composer.lock`) contre la base
  de vulnérabilités connues (CVE).
- **Configuré** : étape « Audit securite Composer » dans `ci.yml` ;
  exécutable en local avec `composer audit`.
- **Comment le montrer** : lancer `composer audit` en local pendant la
  présentation, ou montrer l'exemple réel documenté dans
  `docs/veille.md` (paquets `symfony/*` mis à jour le 12/09/2026).
- **Pourquoi** : couvre le risque n°5 (dépendances vulnérables) et illustre
  concrètement la veille technologique.

### Trivy

- **Rôle** : scan de l'image Docker construite (système et librairies),
  bloquant sur les sévérités CRITICAL/HIGH.
- **Configuré** : étape « Scanner l'image Docker avec Trivy » dans
  `ci.yml`.
- **Comment le montrer** : logs de cette étape dans un run CI (tableau des
  vulnérabilités trouvées, ou absence de vulnérabilité bloquante).
- **Pourquoi** : couvre le risque n°6 (vulnérabilités du système/image).

### SonarCloud (SonarQube)

- **Rôle** : analyse statique du code (SAST) — failles de sécurité, dette
  technique, duplication, complexité.
- **Configuré** : `sonar-project.properties` à la racine ; étape
  conditionnelle dans `ci.yml` (nécessite le secret `SONAR_TOKEN`, encore à
  créer sur sonarcloud.io).
- **Comment le montrer** : une fois le compte créé, le tableau de bord
  SonarCloud du projet (Quality Gate, failles par sévérité).
- **Pourquoi** : c'est l'outil explicitement nommé dans la grille
  d'évaluation (« OWASP, SonarQube, Qualys »).

### OWASP ZAP

- **Rôle** : scan dynamique (DAST) de l'application réellement déployée en
  QA — en-têtes de sécurité, XSS réfléchi, configuration TLS.
- **Configuré** : étape « Scanner l'environnement QA avec OWASP ZAP » dans
  `cd.yml`, exécutée après chaque redéploiement (nécessite la variable
  `QA_URL`, désormais configurée).
- **Comment le montrer** : logs de cette étape dans un run CD, ou le
  rapport ZAP généré en pièce jointe du run GitHub Actions.
- **Pourquoi** : couvre le risque n°13 (absence de test dynamique) et
  complète Qualys pour la partie « outillage de sécurité ».

### Qualys (mention, hors CI)

- **Rôle** : scan de vulnérabilités externe (ports, certificats,
  configuration réseau) sur l'environnement hébergé.
- **Comment le montrer** : scan ponctuel via une édition d'évaluation
  gratuite sur l'URL Render publique, capture d'écran du rapport à insérer
  dans le dossier (voir `docs/security-plan.md`, section 3).
- **Pourquoi** : nommé explicitement dans la grille ; à défaut, le scan
  ZAP déjà automatisé couvre une bonne partie du même besoin.

## Base de données et qualité applicative

### Doctrine Migrations

- **Rôle** : versionne chaque évolution du schéma PostgreSQL (méthodes
  `up()`/`down()`), rejouable et réversible.
- **Configuré** : dossier `migrations/`, étapes de `ci.yml` (rollback
  testé automatiquement) et commandes de démarrage dans `compose.yaml`.
- **Comment le montrer** : `php bin/console doctrine:migrations:status`,
  ou les logs de l'étape « Tester le retour arrière d'une migration » en
  CI ; voir `docs/rollback-test.md` pour le détail du scénario.
- **Pourquoi** : preuve technique concrète du test de non-régression
  attendu (ajout de colonne puis retour arrière).

### PHPUnit

- **Rôle** : tests automatisés (fonctionnels) de l'application.
- **Configuré** : `phpunit.dist.xml`, `tests/`, étape « Lancer PHPUnit »
  dans `ci.yml`.
- **Comment le montrer** : `vendor/bin/phpunit` en local, ou le détail de
  l'étape dans un run CI. C'est aussi le point d'appui du scénario de bug
  volontaire (voir `docs/demo-soutenance.md`).
- **Pourquoi** : garantit qu'aucune régression fonctionnelle n'atteint un
  environnement partagé.

### Docker / Docker Compose

- **Rôle** : conteneurisation de l'application ; un seul `Dockerfile`
  multi-étapes (`base`, `dev`, `qa`, `prod`) garantit que le code exécuté
  est identique entre environnements.
- **Configuré** : `Dockerfile`, `compose.yaml` (4 environnements
  isolés avec leur propre base PostgreSQL).
- **Comment le montrer** : `docker compose up --build app-dev db-dev` en
  local, ou simplement expliquer le schéma des 4 cibles sur le
  `Dockerfile` affiché à l'écran.
- **Pourquoi** : répond au critère « environnement configuré avec
  automatisations » et à la reproductibilité des environnements.

### EasyAdmin

- **Rôle** : back-office d'administration (gestion des utilisateurs,
  exercices, contenus).
- **Configuré** : `src/Controller/Admin/`, accès restreint au rôle
  `ROLE_ADMIN` dans `config/packages/security.yaml`.
- **Comment le montrer** : se connecter avec un compte admin sur `/admin`
  et montrer qu'un compte non-admin y est refusé.
- **Pourquoi** : preuve concrète du contrôle d'accès (risque n°3 du plan
  de sécurisation, OWASP A01).
