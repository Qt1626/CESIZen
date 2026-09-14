# Test de non-régression des migrations : ajout de colonne et retour arrière

## 1. Principe

Chaque migration Doctrine expose une méthode `up()` (application) et une méthode `down()` (annulation). Avant tout déploiement en préproduction ou en production, la réversibilité d'une évolution de schéma doit être vérifiée : c'est la garantie qu'un retour arrière est toujours possible sans perte ni corruption de données.

## 2. Cas de test versionné dans le projet

La migration `migrations/Version20260912180000.php` ajoute une colonne nullable `derniere_connexion` à la table `utilisateur` (propriété `Utilisateur::$derniereConnexion` côté entité) :

```php
public function up(Schema $schema): void
{
    $this->addSql('ALTER TABLE utilisateur ADD derniere_connexion TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
}

public function down(Schema $schema): void
{
    $this->addSql('ALTER TABLE utilisateur DROP derniere_connexion');
}
```

Une colonne nullable, sans valeur par défaut obligatoire et sans contrainte, a été choisie volontairement : elle peut être ajoutée puis retirée sans jamais risquer d'altérer les lignes existantes de la table.

## 3. Automatisation en CI

Le workflow `.github/workflows/ci.yml` exécute ce test à chaque push/Pull Request, sur l'environnement de test :

```yaml
- name: Executer les migrations de test
  run: php bin/console doctrine:migrations:migrate --no-interaction --env=test

- name: "Tester le retour arriere d'une migration (rollback)"
  run: |
    php bin/console doctrine:migrations:migrate prev --no-interaction --env=test
    php bin/console doctrine:migrations:migrate latest --no-interaction --env=test

- name: Verifier Doctrine
  run: php bin/console doctrine:schema:validate --env=test
```

Déroulé :

1. Toutes les migrations sont appliquées jusqu'à la dernière (`migrate` = `migrate latest`).
2. La dernière migration est annulée (`migrate prev`), ce qui exécute sa méthode `down()` — la colonne `derniere_connexion` est supprimée.
3. La migration est immédiatement réappliquée (`migrate latest`), ce qui exécute de nouveau sa méthode `up()`.
4. La validation Doctrine finale (`doctrine:schema:validate`) confirme que le schéma retrouvé après ce cycle annulation/réapplication est identique à l'état attendu.

**Point de vigilance testé en conditions réelles (PostgreSQL local) avant d'écrire cette procédure** : exécuter `doctrine:schema:validate` *entre* l'annulation et la réapplication échoue systématiquement, et c'est normal — le code de l'entité `Utilisateur` reste au niveau du dépôt (avec la propriété `derniereConnexion`), alors que la base, elle, est temporairement revenue en arrière. Ce décalage n'est pas une anomalie : c'est la preuve que le rollback a bien fonctionné. La seule validation qui doit être au vert est celle effectuée **après** avoir réappliqué la migration.

Si l'une de ces étapes échoue, la CI échoue, aucune image n'est publiée, et un ticket est ouvert automatiquement (`auto-ticket.yml`, voir docs/ticketing.md).

## 4. Procédure manuelle avant déploiement en préproduction

Pour toute nouvelle migration, avant son déploiement en préproduction (voir la colonne « Étapes de déploiement » du plan de déploiement) :

1. Sauvegarde de la base courante (`pg_dump`), à l'image du fichier `backups/cesizen_backup.sql` conservé dans le dépôt.
2. Application de la nouvelle migration sur l'environnement QA.
3. Contrôle du bon fonctionnement applicatif et validation du schéma.
4. Retour à la version précédente via `php bin/console doctrine:migrations:migrate prev`.
5. Vérification que le schéma redevient identique à l'état antérieur et que les données existantes ne sont pas altérées.
6. Documentation du résultat dans le ticket GitHub associé à l'évolution.

## 5. Restriction sur préproduction et production

Conformément à la contrainte de non-altération des données de préproduction/production :

- seules des **migrations Doctrine explicitement versionnées** sont appliquées sur ces environnements (`doctrine:migrations:migrate`, voir Dockerfile et `compose.yaml`) ;
- **jamais** de `doctrine:schema:drop`, de `doctrine:database:drop` ni de `doctrine:fixtures:load` sur préproduction/production ;
- une migration n'est déployée sur ces environnements qu'après validation de sa réversibilité selon la procédure ci-dessus.

Seul l'environnement **dev** conserve volontairement `doctrine:schema:update --force` (`compose.yaml`, service `app-dev`) : ses données sont jetables et l'itération rapide y est privilégiée. Un test de restauration complet de la sauvegarde de base est par ailleurs exécuté automatiquement chaque nuit (`.github/workflows/nightly-dev-rebuild.yml`), dans une base dédiée distincte, afin de garantir que `backups/cesizen_backup.sql` reste restaurable à tout moment.
