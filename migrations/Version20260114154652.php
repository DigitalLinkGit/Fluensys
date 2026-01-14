<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260114154652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE capture ADD owner_project_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture ADD CONSTRAINT FK_8BFEA6E5440786D9 FOREIGN KEY (owner_project_id) REFERENCES project (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8BFEA6E5440786D9 ON capture (owner_project_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE capture DROP FOREIGN KEY FK_8BFEA6E5440786D9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8BFEA6E5440786D9 ON capture
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture DROP owner_project_id
        SQL);
    }
}
