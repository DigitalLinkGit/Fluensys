<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250902093141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment DROP FOREIGN KEY FK_C05A2A4166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE flex_capture_element (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flex_capture_element ADD CONSTRAINT FK_CAF7302BF396750 FOREIGN KEY (id) REFERENCES capture_element (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flex_capture DROP FOREIGN KEY FK_553D70BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE6E192A27
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_capture DROP FOREIGN KEY FK_1DE0AA806B301384
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_capture DROP FOREIGN KEY FK_1DE0AA80166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE flex_capture
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_capture
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C05A2A4166D1F9C ON participant_assignment
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment DROP project_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE flex_capture (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, information_system_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, template TINYINT(1) NOT NULL, INDEX IDX_2FB3D0EE6E192A27 (information_system_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_capture (project_id INT NOT NULL, capture_id INT NOT NULL, INDEX IDX_1DE0AA80166D1F9C (project_id), INDEX IDX_1DE0AA806B301384 (capture_id), PRIMARY KEY(project_id, capture_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flex_capture ADD CONSTRAINT FK_553D70BF396750 FOREIGN KEY (id) REFERENCES capture_element (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE6E192A27 FOREIGN KEY (information_system_id) REFERENCES information_system (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_capture ADD CONSTRAINT FK_1DE0AA806B301384 FOREIGN KEY (capture_id) REFERENCES capture (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_capture ADD CONSTRAINT FK_1DE0AA80166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flex_capture_element DROP FOREIGN KEY FK_CAF7302BF396750
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE flex_capture_element
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment ADD project_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment ADD CONSTRAINT FK_C05A2A4166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C05A2A4166D1F9C ON participant_assignment (project_id)
        SQL);
    }
}
