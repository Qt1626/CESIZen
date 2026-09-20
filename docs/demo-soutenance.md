# Scénario de démonstration — soutenance Activité 3

Objectif : montrer, en direct, que le versioning, l'intégration continue, le
déploiement et le ticketing décrits dans le dossier fonctionnent réellement,
et non pas seulement sur documentation. Durée indicative : 6 à 8 minutes sur
les 20 minutes de l'oral.

## Avant la démo (préparation, la veille)

- Vérifier que `master` est vert (dernier run `CI CESIZen` et `CD CESIZen` en succès : onglet **Actions**).
- Vérifier que le service QA répond sur <https://cesizen-latestcesizen-qa.onrender.com> (le plan Render gratuit se met en veille après inactivité : ouvrir l'URL 5 minutes avant l'oral pour le "réveiller", sinon la première requête peut prendre 50 secondes).
- Avoir sous la main, déjà ouverts dans des onglets séparés :
  1. Le dépôt GitHub (`github.com/Qt1626/CESIZen`)
  2. L'onglet **Actions**
  3. L'onglet **Issues**
  4. Le tableau de bord Render
- Préparer une petite modification triviale (ex. un commentaire dans un fichier, ou un texte dans un template) sur une branche `demo/xxx` pour illustrer le flux sans risquer de casser le projet en direct.

## Déroulé pas à pas

1. **Versioning — montrer la structure des branches** (onglet **Branches** ou `git branch -a`) : `master` protégée, `develop`, branches thématiques (`feature/`, `fix/`, `security/`, `docs/`). Expliquer en une phrase pourquoi `develop` existe (éviter un rebase de `master` en cas d'échec après fusion).

2. **Ouvrir une Pull Request** depuis la branche `demo/xxx` vers `master` (ou reprendre la Pull Request `docs/readme` déjà ouverte comme exemple réel si le calendrier ne permet pas d'en créer une en direct).

3. **Montrer la CI s'exécuter automatiquement** sur cette Pull Request (onglet **Checks** de la PR) : tests PHPUnit, scan Gitleaks, audit Composer, build et scan Trivy de l'image Docker. Insister sur le fait que la fusion est bloquée tant que la CI n'est pas verte.

4. **Fusionner la Pull Request** (si le scénario le permet) et montrer, dans l'onglet **Actions**, le déclenchement automatique du workflow `CD CESIZen` : build de l'image, publication sur GitHub Container Registry, appel du webhook Render.

5. **Montrer le redéploiement sur Render** (tableau de bord Render → service `cesizen-qa` → onglet **Events**/**Logs**) puis rafraîchir l'URL publique de QA pour montrer que le changement est bien en ligne.

6. **Déclencher un échec pour montrer le ticketing automatique** : pousser un commit qui casse volontairement un test (ou utiliser `workflow_dispatch` sur un workflow existant si le temps manque) et montrer, dans l'onglet **Issues**, le ticket ouvert automatiquement par `auto-ticket.yml` avec son label de priorité (`P1`/`P2`/`P3`) et le lien direct vers le run en échec.

7. **Refermer la boucle** : corriger (ou annuler) le commit, montrer que le ticket est commenté/fermé, et rappeler que ce même mécanisme couvre aussi l'indisponibilité du service (`uptime-check.yml`, deux échecs consécutifs) et les vulnérabilités détectées (Gitleaks/Trivy/`composer audit`).

## Filet de sécurité

- Si la connexion internet ou Render est capricieux le jour J : avoir en secours des captures d'écran ou une courte vidéo (30–60 s) du même scénario enregistrée à l'avance.
- Ne jamais exécuter cette démonstration sur les environnements préproduction/production : rester sur `master`/QA, dont les données sont réinitialisables (voir `docs/rollback-test.md`).
- Annoncer chaque étape avant de la faire ("je vais maintenant fusionner la Pull Request, ce qui doit déclencher...") : cela sécurise la démo même si un écran met du temps à réagir.
