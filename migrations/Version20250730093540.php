<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250730093540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE text_area_field (id INT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_area_field ADD CONSTRAINT FK_4AC50418BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
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
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD type VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD type VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flex_capture CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flex_capture ADD CONSTRAINT FK_553D70BF396750 FOREIGN KEY (id) REFERENCES capture_element (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE field_response (id INT AUTO_INCREMENT NOT NULL, field_id INT DEFAULT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_E8438C36443707B0 (field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE text_area_field_response (id INT NOT NULL, value VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field_response ADD CONSTRAINT FK_E8438C36443707B0 FOREIGN KEY (field_id) REFERENCES field (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_area_field_response ADD CONSTRAINT FK_B30F392EBF396750 FOREIGN KEY (id) REFERENCES field_response (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_area_field DROP FOREIGN KEY FK_4AC50418BF396750
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE text_area_field
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP type
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field DROP type
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flex_capture DROP FOREIGN KEY FK_553D70BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flex_capture CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
    }
}
