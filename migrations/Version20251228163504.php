<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251228163504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE capture ADD tenant_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture ADD CONSTRAINT FK_8BFEA6E59033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE RESTRICT
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8BFEA6E59033212A ON capture (tenant_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE capture DROP FOREIGN KEY FK_8BFEA6E59033212A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8BFEA6E59033212A ON capture
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture DROP tenant_id
        SQL);
    }
}
