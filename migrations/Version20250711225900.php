<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250711225900 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Update user roles column to JSON type';
    }

    public function up(Schema $schema) : void
    {
        // Ensure roles column uses JSON type
        $this->addSql('ALTER TABLE `user` MODIFY roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // No exact previous type known; revert to LONGTEXT to be safe
        $this->addSql('ALTER TABLE `user` MODIFY roles LONGTEXT NOT NULL');
    }
}
