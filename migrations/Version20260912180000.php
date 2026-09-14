<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Ajoute la colonne derniere_connexion sur la table utilisateur.
 *
 * Cette migration sert également de cas de test pour la procédure de
 * non-régression décrite dans docs/rollback-test.md : elle est appliquée
 * puis annulée (rollback) automatiquement par la CI (voir .github/workflows/ci.yml)
 * afin de vérifier que la méthode down() restitue exactement le schéma antérieur.
 */
final class Version20260912180000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute utilisateur.derniere_connexion (colonne nullable, sans impact sur les données existantes).';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE utilisateur ADD derniere_connexion TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE utilisateur DROP derniere_connexion');
    }
}
