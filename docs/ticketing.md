# Gestion des évolutions et ticketing - CESIZen

## 1. Outil

La gestion des évolutions et des anomalies s'appuie sur **GitHub Issues**, directement intégré au dépôt de code, ce qui permet de relier chaque ticket à la branche, à la Pull Request et au déploiement qui le résolvent.

Chaque ticket est qualifié par un système de labels :

- **Type** : `bug`, `évolution`, `security`, `documentation`.
- **Priorité** : `P1` (critique) à `P4` (faible), échelle harmonisée avec docs/sla.md et docs/incident-response.md.
- **Statut** : suivi visuel via un tableau GitHub Projects (à faire / en cours / en revue / terminé).

## 2. Méthodologie de gestion des tickets entre le prestataire et le client

Le circuit d'un ticket, du signalement à la clôture, implique deux rôles principaux : le **client** (qui signale une anomalie ou exprime un besoin d'évolution, et valide la recette) et le **prestataire/équipe technique** (qui qualifie, développe, teste et déploie).

1. **Signalement** : le client déclare une anomalie ou un besoin (ticket GitHub direct, ou formulaire/e-mail retranscrit en ticket par le prestataire).
2. **Qualification** : le ticket est labellisé (type, priorité) selon la grille de criticité, avec accusé de réception au client dans le délai défini par le SLA (docs/sla.md).
3. **Planification** : le ticket est assigné et intégré à l'itération en cours en fonction de sa priorité.
4. **Développement** : correction ou évolution réalisée sur une branche dédiée, référencée dans le ticket.
5. **Validation** : passage obligatoire par la CI (`.github/workflows/ci.yml`), puis revue de code avant fusion sur `master`.
6. **Déploiement** : mise à jour automatique de l'environnement QA (`.github/workflows/cd.yml`), puis validation fonctionnelle.
7. **Recette client** : validation par le client en préproduction avant mise en production.
8. **Clôture** : déploiement en production, compte-rendu au client, fermeture du ticket avec documentation de la cause et de la correction apportée.

## 3. Ouverture automatique de tickets

Pour raccourcir le délai entre la détection d'un problème et sa prise en charge, trois workflows GitHub Actions ouvrent automatiquement un ticket :

### `.github/workflows/auto-ticket.yml`
Se déclenche lorsque la CI, la CD ou la reconstruction nocturne de l'environnement dev échouent (`workflow_run` sur conclusion `failure`). Crée une issue labellisée selon le workflow en échec (`ci-echec`/P2, `deploiement`/P1, `dev`/P3), avec un lien direct vers le run concerné. Si un ticket du même type est déjà ouvert, un commentaire est ajouté dessus plutôt que de créer un doublon.

### `.github/workflows/uptime-check.yml`
Interroge l'URL surveillée (variable de dépôt `MONITORED_URL`) toutes les 15 minutes. Un ticket P1 « service indisponible » n'est créé qu'après **deux échecs consécutifs** (comparaison avec la conclusion du run précédent), afin d'éviter les faux positifs liés à un simple redémarrage. Le ticket est automatiquement commenté et fermé dès que le service répond de nouveau.

### `.github/workflows/cd.yml` (scan OWASP ZAP)
Le scan de sécurité dynamique exécuté après chaque déploiement QA peut lui-même échouer la CD (`fail_action`) ou générer ses propres constats ; en cas d'échec du workflow CD, le même mécanisme `auto-ticket.yml` prend le relais.

Ces trois mécanismes couvrent l'ensemble des sources d'incident identifiées dans le plan de déploiement (échec de build/test, échec de déploiement, échec de disponibilité) sans nécessiter d'outil tiers payant.

## 4. Veille technologique

Voir docs/veille.md pour la méthode de veille (sources, fréquence, boucle veille → ticket → correction → validation CI → documentation).
