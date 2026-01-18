<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260118124554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE text_chapter DROP FOREIGN KEY FK_DD209A11BF396750
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE text_chapter
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter ADD content LONGTEXT DEFAULT NULL, DROP type
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE text_chapter (id INT NOT NULL, content VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_chapter ADD CONSTRAINT FK_DD209A11BF396750 FOREIGN KEY (id) REFERENCES chapter (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter ADD type VARCHAR(255) NOT NULL, DROP content
        SQL);
    }
}
