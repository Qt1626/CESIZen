<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250321103111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change la longueur du champ type_action de admin_log';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE admin_log ALTER COLUMN type_action TYPE VARCHAR(255)'
        );

        $this->addSql(
            'ALTER TABLE admin_log ALTER COLUMN type_action SET NOT NULL'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE admin_log ALTER COLUMN type_action TYPE VARCHAR(50)'
        );

        $this->addSql(
            'ALTER TABLE admin_log ALTER COLUMN type_action DROP NOT NULL'
        );
    }
}