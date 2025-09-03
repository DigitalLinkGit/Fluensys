<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250903203906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD chapter_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD CONSTRAINT FK_33ED8BFF579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_33ED8BFF579F4768 ON capture_element (chapter_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP FOREIGN KEY FK_33ED8BFF579F4768
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_33ED8BFF579F4768 ON capture_element
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP chapter_id
        SQL);
    }
}
