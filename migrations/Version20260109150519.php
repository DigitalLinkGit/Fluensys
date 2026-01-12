<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260109150519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_capture_role ON participant_assignment
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment ADD purpose VARCHAR(20) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_capture_role_purpose ON participant_assignment (capture_id, role_id, purpose)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_capture_role_purpose ON participant_assignment
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment DROP purpose
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_capture_role ON participant_assignment (capture_id, role_id)
        SQL);
    }
}
