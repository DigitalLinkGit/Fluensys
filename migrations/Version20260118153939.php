<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260118153939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE file_field ADD path VARCHAR(1024) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image_field ADD path VARCHAR(1024) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE file_field DROP path
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image_field DROP path
        SQL);
    }
}
