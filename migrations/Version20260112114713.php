<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260112114713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE project_recurring_capture_templates (project_id INT NOT NULL, capture_id INT NOT NULL, INDEX IDX_E62BB78C166D1F9C (project_id), INDEX IDX_E62BB78C6B301384 (capture_id), PRIMARY KEY(project_id, capture_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_recurring_capture_templates ADD CONSTRAINT FK_E62BB78C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_recurring_capture_templates ADD CONSTRAINT FK_E62BB78C6B301384 FOREIGN KEY (capture_id) REFERENCES capture (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE project_recurring_capture_templates DROP FOREIGN KEY FK_E62BB78C166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project_recurring_capture_templates DROP FOREIGN KEY FK_E62BB78C6B301384
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE project_recurring_capture_templates
        SQL);
    }
}
