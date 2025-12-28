<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251225222045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment DROP FOREIGN KEY FK_C05A2A4D60322AC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment ADD capture_id INT NOT NULL, ADD user_id INT DEFAULT NULL, ADD contact_id INT DEFAULT NULL, DROP external_last_name, DROP external_first_name, DROP external_email, DROP external_function
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment ADD CONSTRAINT FK_C05A2A46B301384 FOREIGN KEY (capture_id) REFERENCES capture (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment ADD CONSTRAINT FK_C05A2A4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment ADD CONSTRAINT FK_C05A2A4E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment ADD CONSTRAINT FK_C05A2A4D60322AC FOREIGN KEY (role_id) REFERENCES participant_role (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C05A2A46B301384 ON participant_assignment (capture_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C05A2A4A76ED395 ON participant_assignment (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C05A2A4E7A1254A ON participant_assignment (contact_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_capture_role ON participant_assignment (capture_id, role_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment DROP FOREIGN KEY FK_C05A2A46B301384
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment DROP FOREIGN KEY FK_C05A2A4A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment DROP FOREIGN KEY FK_C05A2A4E7A1254A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment DROP FOREIGN KEY FK_C05A2A4D60322AC
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C05A2A46B301384 ON participant_assignment
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C05A2A4A76ED395 ON participant_assignment
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C05A2A4E7A1254A ON participant_assignment
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_capture_role ON participant_assignment
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment ADD external_last_name VARCHAR(255) DEFAULT NULL, ADD external_first_name VARCHAR(255) DEFAULT NULL, ADD external_email VARCHAR(255) DEFAULT NULL, ADD external_function VARCHAR(255) DEFAULT NULL, DROP capture_id, DROP user_id, DROP contact_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment ADD CONSTRAINT FK_C05A2A4D60322AC FOREIGN KEY (role_id) REFERENCES participant_role (id)
        SQL);
    }
}
