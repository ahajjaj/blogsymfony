<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add slug column to article table
 */
final class Version20231130000000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add slug field to article';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE article ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E66BF396750 ON article (slug)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX UNIQ_23A0E66BF396750 ON article');
        $this->addSql('ALTER TABLE article DROP slug');
    }
}
