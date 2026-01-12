<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260108104748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, tenant_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_2FB3D0EE9033212A (tenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_capture (project_id INT NOT NULL, capture_id INT NOT NULL, INDEX IDX_1DE0AA80166D1F9C (project_id), INDEX IDX_1DE0AA806B301384 (capture_id), PRIMARY KEY(project_id, capture_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE project_recurring_capture (project_id INT NOT NULL, capture_id INT NOT NULL, INDEX IDX_67DB6F53166D1F9C (project_id), INDEX IDX_67DB6F536B301384 (capture_id), PRIMARY KEY(project_id, capture_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE RESTRICT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_capture ADD CONSTRAINT FK_1DE0AA80166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_capture ADD CONSTRAINT FK_1DE0AA806B301384 FOREIGN KEY (capture_id) REFERENCES capture (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_recurring_capture ADD CONSTRAINT FK_67DB6F53166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_recurring_capture ADD CONSTRAINT FK_67DB6F536B301384 FOREIGN KEY (capture_id) REFERENCES capture (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE9033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_capture DROP FOREIGN KEY FK_1DE0AA80166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_capture DROP FOREIGN KEY FK_1DE0AA806B301384
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_recurring_capture DROP FOREIGN KEY FK_67DB6F53166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_recurring_capture DROP FOREIGN KEY FK_67DB6F536B301384
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_capture
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_recurring_capture
        SQL);
    }
}
