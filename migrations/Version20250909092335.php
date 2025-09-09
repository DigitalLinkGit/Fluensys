<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250909092335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE capture ADD title_id INT DEFAULT NULL, DROP title
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture ADD CONSTRAINT FK_8BFEA6E5A9F87BD FOREIGN KEY (title_id) REFERENCES title (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8BFEA6E5A9F87BD ON capture (title_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter ADD title_id INT DEFAULT NULL, DROP title
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter ADD CONSTRAINT FK_F981B52EA9F87BD FOREIGN KEY (title_id) REFERENCES title (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_F981B52EA9F87BD ON chapter (title_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE capture DROP FOREIGN KEY FK_8BFEA6E5A9F87BD
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8BFEA6E5A9F87BD ON capture
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture ADD title VARCHAR(255) NOT NULL, DROP title_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52EA9F87BD
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_F981B52EA9F87BD ON chapter
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter ADD title VARCHAR(255) NOT NULL, DROP title_id
        SQL);
    }
}
