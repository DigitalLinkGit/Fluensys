<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250622140411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE field_response (id INT AUTO_INCREMENT NOT NULL, field_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E8438C36443707B0 (field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE text_area_field_response (id INT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field_response ADD CONSTRAINT FK_E8438C36443707B0 FOREIGN KEY (field_id) REFERENCES field (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_area_field_response ADD CONSTRAINT FK_B30F392EBF396750 FOREIGN KEY (id) REFERENCES field_response (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE field_response DROP FOREIGN KEY FK_E8438C36443707B0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_area_field_response DROP FOREIGN KEY FK_B30F392EBF396750
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE field_response
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE text_area_field_response
        SQL);
    }
}
