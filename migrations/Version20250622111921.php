<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250622111921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP FOREIGN KEY FK_33ED8BFF1E745D3A
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE chapter_renderer
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_33ED8BFF1E745D3A ON capture_element
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP chapter_renderer_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE chapter_renderer (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD chapter_renderer_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD CONSTRAINT FK_33ED8BFF1E745D3A FOREIGN KEY (chapter_renderer_id) REFERENCES chapter_renderer (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_33ED8BFF1E745D3A ON capture_element (chapter_renderer_id)
        SQL);
    }
}
