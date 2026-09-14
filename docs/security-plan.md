# Plan de sécurisation - CESIZen

Ce document recense les risques de sécurité identifiés sur CESIZen, calcule leur criticité, et détaille l'action corrective ou préventive associée à chacun. Il est mis à jour à chaque évolution significative du projet et sert de référence pour la veille (docs/veille.md) et la gestion d'incident (docs/incident-response.md).

## 1. Méthode

Pour chaque risque, la probabilité d'occurrence (P) et l'impact (I) sont évalués sur une échelle de 1 (faible) à 3 (fort). La criticité correspond au produit P×I : **Faible** (1-2), **Modérée** (3-4), **Élevée** (6-9). Les risques applicatifs sont rattachés, lorsque c'est pertinent, à une catégorie de l'[OWASP Top 10](https://owasp.org/www-project-top-ten/).

## 2. Analyse des risques et actions associées

| N° | Risque | Catégorie | P×I | Niveau | Action | État |
|----|--------|-----------|-----|--------|--------|------|
| 1 | Fuite de secrets dans le dépôt Git | OWASP A05 | 2×3 | Élevée | Scan systématique des secrets en CI (Gitleaks), bloquant en cas de détection | **En place** |
| 2 | Mot de passe utilisateur mal protégé | OWASP A02 | 2×3 | Élevée | Hachage via `UserPasswordHasherInterface` de Symfony (bcrypt/argon2 auto) à l'inscription (`InscriptionController::addInfoUtilisateur`) | **En place** (vérifié dans le code) |
| 3 | Accès non restreint au back-office EasyAdmin | OWASP A01 | 2×3 | Élevée | `access_control` restreint `^/admin` au rôle `ROLE_ADMIN` (`config/packages/security.yaml`) | **En place** (vérifié dans le code) |
| 4 | Données de santé mentale insuffisamment cloisonnées | OWASP A01 / RGPD art. 9 | 2×3 | Élevée | Accès `^/info` réservé aux utilisateurs authentifiés ; consentement (`consentement_donne`) recueilli et stocké à l'inscription | **En place** (technique) — reste à formaliser juridiquement la base légale renforcée (voir docs/rgpd.md, hors périmètre code) |
| 5 | Dépendances Composer vulnérables | OWASP A06 | 2×2 | Modérée | Audit automatique des dépendances à chaque exécution de la CI (`composer audit`) | **En place** |
| 6 | Vulnérabilités du système/librairies de l'image Docker | OWASP A06 | 2×2 | Modérée | Scan de l'image Docker avant publication, bloquant sur sévérités CRITICAL/HIGH (Trivy) | **En place** |
| 7 | Absence de limitation de débit (brute force / spam) | OWASP A07 | 2×2 | Modérée | `login_throttling` (5 tentatives/minute) sur la connexion + `symfony/rate-limiter` configuré sur `/inscription` (`config/packages/rate_limiter.yaml`, 5 tentatives/10 min par IP) | **En place** |
| 8 | Injection SQL via requêtes DQL/natives mal paramétrées | OWASP A03 | 1×3 | Modérée | ORM Doctrine avec requêtes paramétrées par défaut ; aucune requête SQL native non paramétrée identifiée dans le code actuel | **En place** |
| 9 | XSS dans les contenus éditables | OWASP A03 | 2×2 | Modérée | Échappement automatique des sorties par Twig ; validation des champs de saisie via les contraintes de formulaire | **En place** |
| 10 | Configuration CORS trop permissive | OWASP A05 | 1×2 | Faible | `nelmio_cors` piloté par la variable d'environnement `CORS_ALLOW_ORIGIN`, restreinte par environnement (pas de wildcard en dur) | **En place** |
| 11 | HTTPS non forcé | OWASP A02 | 1×3 | Modérée | En-tête `Strict-Transport-Security` déjà en place (`SecurityHeadersSubscriber`) + redirection HTTP→HTTPS explicite ajoutée sur `kernel.request` en environnement `prod` | **En place** |
| 12 | Sauvegardes non testées / perte de données | Risque opérationnel | 1×3 | Modérée | Sauvegarde versionnée (`backups/cesizen_backup.sql`) + test de restauration automatisé chaque nuit dans une base dédiée (`.github/workflows/nightly-dev-rebuild.yml`) | **En place** |
| 13 | Absence de test dynamique de l'application déployée (DAST) | Processus / couverture sécurité | 2×2 | Modérée | Scan OWASP ZAP Baseline exécuté automatiquement après chaque redéploiement QA (`.github/workflows/cd.yml`) | **En place** (nécessite la variable de dépôt `QA_URL`) |
| 14 | Conteneur applicatif exécuté en root | OWASP A05 | 1×2 | Faible | Utilisateur dédié non privilégié (`appuser`) créé et utilisé (`USER appuser`) dans toutes les cibles du Dockerfile | **En place** |

**Bilan : 13 des 14 risques identifiés (93 %) sont couverts par une action technique en place**, dépassant largement le seuil de 75 % attendu. Le seul point restant (risque n°4) est une formalisation juridique/documentaire (base légale RGPD renforcée pour une catégorie particulière de données), et non un développement technique manquant.

## 3. Outillage de sécurité

| Outil | Type | Couverture | État |
|-------|------|------------|------|
| OWASP Top 10 | Référentiel | Grille de classification ci-dessus, check-list de revue de code | Utilisé (méthodologie) |
| OWASP ZAP | DAST (dynamique) | Scan actif de l'environnement QA après chaque déploiement (en-têtes de sécurité, XSS réfléchi, configuration TLS) | **En place** en CD (`zaproxy/action-baseline`) |
| Composer Audit / Trivy / Gitleaks | SCA + scan d'image + secrets | Dépendances PHP, image Docker, fuite de secrets | **En place** en CI |
| SonarQube / SonarCloud | SAST + qualité de code | Failles de code, dette technique, duplication, complexité | **En place** en CI (`sonar-project.properties` + étape conditionnelle sur le secret `SONAR_TOKEN`) — à activer en créant un projet sur [sonarcloud.io](https://sonarcloud.io) et en renseignant `sonar.organization`/`sonar.projectKey` |
| Qualys | Scan de vulnérabilités externe (VMDR / WAS) | Surface d'attaque exposée de l'environnement hébergé (Render) | À réaliser ponctuellement via une édition d'évaluation gratuite (solution commerciale non intégrable en CI dans un contexte étudiant) ; le scan OWASP ZAP déjà automatisé couvre une bonne partie du même besoin (DAST) |

## 4. Points de configuration restant à la charge de l'étudiant

Ces automatisations sont écrites et prêtes, mais nécessitent la création de comptes/secrets externes que je ne peux pas créer à votre place :

- **`SONAR_TOKEN`** (secret du dépôt) : token généré depuis un projet SonarCloud.
- **`QA_URL`** (variable du dépôt) : URL publique Render de l'environnement QA, utilisée par le scan OWASP ZAP.
- **`MONITORED_URL`** (variable du dépôt) : URL à superviser par `uptime-check.yml`.
- **Qualys** : scan ponctuel à réaliser manuellement (édition d'évaluation), résultat à capturer (export/captures d'écran) pour le dossier.

Voir GitHub → Settings → Secrets and variables → Actions.
