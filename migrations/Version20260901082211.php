<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260901082211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin_log (id INT AUTO_INCREMENT NOT NULL, type_action VARCHAR(255) NOT NULL, date_admin_log DATE NOT NULL, id_utilisateur_id INT DEFAULT NULL, INDEX IDX_F9383BB0C6EE5C49 (id_utilisateur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE exercice_respiration (id INT AUTO_INCREMENT NOT NULL, nom_exercice_respiration VARCHAR(255) NOT NULL, description_exercice_respiration LONGTEXT DEFAULT NULL, duree_inspiration INT DEFAULT NULL, duree_apnee INT DEFAULT NULL, duree_expiration INT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE exercice_respiration_utilisateur (exercice_respiration_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_F420C12DD2D7F70 (exercice_respiration_id), INDEX IDX_F420C12DFB88E14F (utilisateur_id), PRIMARY KEY (exercice_respiration_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE info_utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, date_naissance DATE DEFAULT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE information_page (id INT AUTO_INCREMENT NOT NULL, titre_page VARCHAR(255) NOT NULL, ordre_affichage INT NOT NULL, est_visible TINYINT NOT NULL, contenu_page LONGTEXT NOT NULL, commentaire LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, date_creation DATETIME NOT NULL, consentement_donne TINYINT DEFAULT NULL, est_admin TINYINT DEFAULT NULL, info_utilisateur_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_1D1C63B3279A1CB (info_utilisateur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE admin_log ADD CONSTRAINT FK_F9383BB0C6EE5C49 FOREIGN KEY (id_utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE exercice_respiration_utilisateur ADD CONSTRAINT FK_F420C12DD2D7F70 FOREIGN KEY (exercice_respiration_id) REFERENCES exercice_respiration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercice_respiration_utilisateur ADD CONSTRAINT FK_F420C12DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3279A1CB FOREIGN KEY (info_utilisateur_id) REFERENCES info_utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_log DROP FOREIGN KEY FK_F9383BB0C6EE5C49');
        $this->addSql('ALTER TABLE exercice_respiration_utilisateur DROP FOREIGN KEY FK_F420C12DD2D7F70');
        $this->addSql('ALTER TABLE exercice_respiration_utilisateur DROP FOREIGN KEY FK_F420C12DFB88E14F');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3279A1CB');
        $this->addSql('DROP TABLE admin_log');
        $this->addSql('DROP TABLE exercice_respiration');
        $this->addSql('DROP TABLE exercice_respiration_utilisateur');
        $this->addSql('DROP TABLE info_utilisateur');
        $this->addSql('DROP TABLE information_page');
        $this->addSql('DROP TABLE utilisateur');
    }
}
