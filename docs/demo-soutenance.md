# Scénario de démonstration — soutenance Activité 3

Objectif : montrer, en direct, que le versioning, l'intégration continue, le
déploiement et le ticketing décrits dans le dossier fonctionnent réellement,
et non pas seulement sur documentation. Durée indicative : 6 à 8 minutes sur
les 20 minutes de l'oral.

Voir aussi `docs/outils.md` pour une fiche par outil (rôle, configuration,
comment le montrer) à utiliser en complément de ce déroulé.

## Avant la démo (préparation, la veille)

- Vérifier que `master` est vert (dernier run `CI CESIZen` et `CD CESIZen` en succès : onglet **Actions**).
- Vérifier que le service QA répond sur <https://cesizen-latestcesizen-qa.onrender.com> (le plan Render gratuit se met en veille après inactivité : ouvrir l'URL 5 minutes avant l'oral pour le "réveiller", sinon la première requête peut prendre 50 secondes).
- Avoir sous la main, déjà ouverts dans des onglets séparés :
  1. Le dépôt GitHub (`github.com/Qt1626/CESIZen`)
  2. L'onglet **Actions**
  3. L'onglet **Issues**
  4. Le tableau de bord Render
- Préparer une petite modification triviale (ex. un commentaire dans un fichier, ou un texte dans un template) sur une branche `demo/xxx` pour illustrer le flux sans risquer de casser le projet en direct.

## Déroulé pas à pas (vue d'ensemble)

1. **Versioning — montrer la structure des branches** (onglet **Branches** ou `git branch -a`) : `master` protégée, `develop`, branches thématiques (`feature/`, `fix/`, `security/`, `docs/`). Expliquer en une phrase pourquoi `develop` existe (éviter un rebase de `master` en cas d'échec après fusion).

2. **Ouvrir une Pull Request** depuis une branche de démo vers `master` (voir déroulé technique détaillé ci-dessous, avec un bug volontaire pour déclencher le ticketing automatique).

3. **Montrer la CI s'exécuter automatiquement** sur cette Pull Request (onglet **Checks** de la PR) : tests PHPUnit, scan Gitleaks, audit Composer, build et scan Trivy de l'image Docker. Insister sur le fait que la fusion est bloquée tant que la CI n'est pas verte.

4. **Fusionner une Pull Request saine** (ex. la PR `docs/readme` déjà ouverte) et montrer, dans l'onglet **Actions**, le déclenchement automatique du workflow `CD CESIZen` : build de l'image, publication sur GitHub Container Registry, appel du webhook Render.

5. **Montrer le redéploiement sur Render** (tableau de bord Render → service `cesizen-qa` → onglet **Events**/**Logs**) puis rafraîchir l'URL publique de QA pour montrer que le changement est bien en ligne.

6. **Déclencher un échec pour montrer le ticketing automatique** — voir le déroulé technique détaillé ci-dessous.

7. **Refermer la boucle** : corriger (ou annuler) le commit, montrer que le ticket peut être commenté/fermé, et rappeler que ce même mécanisme couvre aussi l'indisponibilité du service (`uptime-check.yml`, deux échecs consécutifs) et les vulnérabilités détectées (Gitleaks/Trivy/`composer audit`).

## Déroulé technique détaillé : provoquer un échec de CI pour démontrer le ticketing automatique

Le bug le plus sûr et le plus rapide à déclencher est un test PHPUnit qui
échoue volontairement : cela ne touche ni la configuration ni les données
d'aucun environnement, et se répare en une seule ligne.

### 1. Préparer la branche de démo (à faire avant l'oral, ou en tout début de la partie démonstration)

```bash
git checkout master
git pull
git checkout -b demo/echec-ci
```

### 2. Casser volontairement un test

Modifier `tests/Controller/HomeControllerTest.php` : changer l'URL testée
par `testRegisterPageIsSuccessful` pour une route qui n'existe pas (le test
va échouer avec un code 404 au lieu de 200) :

```php
public function testRegisterPageIsSuccessful(): void
{
    $client = static::createClient();

    $client->request('GET', '/inscription-bug'); // volontairement casse pour la demo

    $this->assertResponseIsSuccessful();
}
```

### 3. Committer et pousser

```bash
git add tests/Controller/HomeControllerTest.php
git commit -m "demo: casser volontairement un test pour declencher l'automatisation"
git push -u origin demo/echec-ci
```

### 4. Ouvrir la Pull Request

Sur GitHub, ouvrir une Pull Request `demo/echec-ci` → `master`. **Astuce de
minutage** : pousser cette branche et ouvrir la PR juste avant d'attaquer la
partie sécurisation/maintenance de l'oral, puis continuer la présentation —
la CI (installation des dépendances, migrations, lint, PHPUnit) prend
généralement 2 à 4 minutes, le temps de revenir dessus au bon moment plutôt
que d'attendre en silence devant l'écran.

### 5. Montrer l'échec de la CI

Onglet **Checks** de la Pull Request : l'étape « Lancer PHPUnit » échoue
(les étapes suivantes — audit Composer, build Docker, scan Trivy — ne
s'exécutent pas puisque le pipeline s'arrête à la première étape en échec).
Insister : aucune image n'est publiée, la fusion reste bloquée.

### 6. Montrer le ticket ouvert automatiquement

Attendre 30 à 60 secondes après la fin du run (le temps que
`auto-ticket.yml` se déclenche sur l'événement `workflow_run`), puis
rafraîchir l'onglet **Issues** du dépôt : un ticket intitulé `[Auto] Echec
de la CI (demo/echec-ci, <sha>)` doit apparaître, labellisé `bug`,
`ci-echec` et `P2`, avec un lien direct vers le run en échec — exactement
le mécanisme décrit dans `docs/ticketing.md`.

### 7. Corriger et refermer la boucle

```bash
git checkout master -- tests/Controller/HomeControllerTest.php
git commit -m "demo: correction du test, la CI repasse au vert"
git push
```

Rafraîchir l'onglet **Checks** : la CI repasse au vert. Expliquer qu'en
situation réelle, le ticket serait alors clôturé manuellement une fois la
cause et la correction documentées (voir `docs/incident-response.md`,
section « Clôture ») — seul `uptime-check.yml` ferme automatiquement son
propre type de ticket (indisponibilité), les échecs de CI/CD sont clôturés
après revue humaine.

### 8. Nettoyage après la soutenance

- Fermer la Pull Request `demo/echec-ci` sans la fusionner.
- Supprimer la branche (localement et sur GitHub) : `git branch -d demo/echec-ci` puis `git push origin --delete demo/echec-ci`.
- Fermer ou supprimer le ticket de démonstration créé automatiquement.

## Filet de sécurité

- Si la connexion internet ou Render est capricieux le jour J : avoir en secours des captures d'écran ou une courte vidéo (30–60 s) du même scénario enregistrée à l'avance.
- Ne jamais exécuter cette démonstration sur les environnements préproduction/production : rester sur `master`/QA, dont les données sont réinitialisables (voir `docs/rollback-test.md`).
- Ne jamais fusionner la branche `demo/echec-ci` : elle ne sert qu'à faire échouer la CI sur une Pull Request, jamais à modifier `master`.
- Annoncer chaque étape avant de la faire ("je vais maintenant pousser un commit qui casse un test, ce qui doit déclencher...") : cela sécurise la démo même si un écran met du temps à réagir.
