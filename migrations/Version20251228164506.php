<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251228164506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD tenant_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD CONSTRAINT FK_33ED8BFF9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE RESTRICT
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_33ED8BFF9033212A ON capture_element (tenant_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP FOREIGN KEY FK_33ED8BFF9033212A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_33ED8BFF9033212A ON capture_element
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP tenant_id
        SQL);
    }
}
