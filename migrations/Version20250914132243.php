<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250914132243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE `condition` (id INT AUTO_INCREMENT NOT NULL, source_element_id INT NOT NULL, source_field_id INT NOT NULL, target_element_id INT NOT NULL, expected_value VARCHAR(255) NOT NULL, INDEX IDX_BDD6884363AB5B00 (source_element_id), INDEX IDX_BDD688437173162 (source_field_id), INDEX IDX_BDD688432DF3F2B5 (target_element_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` ADD CONSTRAINT FK_BDD6884363AB5B00 FOREIGN KEY (source_element_id) REFERENCES capture_element (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` ADD CONSTRAINT FK_BDD688437173162 FOREIGN KEY (source_field_id) REFERENCES field (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` ADD CONSTRAINT FK_BDD688432DF3F2B5 FOREIGN KEY (target_element_id) REFERENCES capture_element (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD active TINYINT(1) DEFAULT 1 NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` DROP FOREIGN KEY FK_BDD6884363AB5B00
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` DROP FOREIGN KEY FK_BDD688437173162
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` DROP FOREIGN KEY FK_BDD688432DF3F2B5
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `condition`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP active
        SQL);
    }
}
