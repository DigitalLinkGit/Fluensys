<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250903200204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52E84A0A3ED
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE text_chapter (id INT NOT NULL, content VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_chapter ADD CONSTRAINT FK_DD209A11BF396750 FOREIGN KEY (id) REFERENCES chapter (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE chapter_content
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_F981B52E84A0A3ED ON chapter
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter ADD type VARCHAR(255) NOT NULL, DROP content_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE chapter_content (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, format VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_chapter DROP FOREIGN KEY FK_DD209A11BF396750
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE text_chapter
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter ADD content_id INT NOT NULL, DROP type
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter ADD CONSTRAINT FK_F981B52E84A0A3ED FOREIGN KEY (content_id) REFERENCES chapter_content (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_F981B52E84A0A3ED ON chapter (content_id)
        SQL);
    }
}
