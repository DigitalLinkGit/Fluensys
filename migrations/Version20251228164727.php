<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251228164727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE contact ADD tenant_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact ADD CONSTRAINT FK_4C62E6389033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE RESTRICT
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4C62E6389033212A ON contact (tenant_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6389033212A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_4C62E6389033212A ON contact
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact DROP tenant_id
        SQL);
    }
}
