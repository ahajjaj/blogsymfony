<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250711211701 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add image column to article';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE article ADD image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE article DROP image');
    }
}
