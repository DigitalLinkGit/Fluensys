<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260119215608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE activity_log (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, capture_id INT DEFAULT NULL, capture_element_id INT DEFAULT NULL, actor_user_id INT DEFAULT NULL, actor_contact_id INT DEFAULT NULL, occurred_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', action VARCHAR(255) NOT NULL, subject_type VARCHAR(255) NOT NULL, subject_label VARCHAR(255) NOT NULL, actor_type VARCHAR(255) NOT NULL, INDEX IDX_FD06F647859B83FF (actor_user_id), INDEX IDX_FD06F647D58D3059 (actor_contact_id), INDEX idx_activity_occurred_at (occurred_at), INDEX idx_activity_action (action), INDEX idx_activity_project (project_id), INDEX idx_activity_capture (capture_id), INDEX idx_activity_capture_element (capture_element_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F647166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F6476B301384 FOREIGN KEY (capture_id) REFERENCES capture (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F647DE152EAB FOREIGN KEY (capture_element_id) REFERENCES capture_element (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F647859B83FF FOREIGN KEY (actor_user_id) REFERENCES user (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F647D58D3059 FOREIGN KEY (actor_contact_id) REFERENCES contact (id) ON DELETE SET NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F647166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F6476B301384
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F647DE152EAB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F647859B83FF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F647D58D3059
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE activity_log
        SQL);
    }
}
