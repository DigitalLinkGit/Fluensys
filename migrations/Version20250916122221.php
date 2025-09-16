<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250916122221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` DROP FOREIGN KEY FK_BDD6884363AB5B00
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` DROP FOREIGN KEY FK_BDD688437173162
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_BDD6884363AB5B00 ON `condition`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` DROP source_element_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` ADD CONSTRAINT FK_BDD688437173162 FOREIGN KEY (source_field_id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` DROP FOREIGN KEY FK_BDD688437173162
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` ADD source_element_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` ADD CONSTRAINT FK_BDD6884363AB5B00 FOREIGN KEY (source_element_id) REFERENCES capture_element (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` ADD CONSTRAINT FK_BDD688437173162 FOREIGN KEY (source_field_id) REFERENCES field (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BDD6884363AB5B00 ON `condition` (source_element_id)
        SQL);
    }
}
