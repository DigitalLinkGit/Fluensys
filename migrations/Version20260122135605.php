<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260122135605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F6476B301384
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F647DE152EAB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F647166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F6479B6B5FBA
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_FD06F6479B6B5FBA ON activity_log
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log ADD account_name VARCHAR(255) DEFAULT NULL, ADD project_name VARCHAR(255) DEFAULT NULL, ADD capture_name VARCHAR(255) DEFAULT NULL, ADD capture_element_name VARCHAR(255) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log DROP account_name, DROP project_name, DROP capture_name, DROP capture_element_name
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F6476B301384 FOREIGN KEY (capture_id) REFERENCES capture (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F647DE152EAB FOREIGN KEY (capture_element_id) REFERENCES capture_element (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F647166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F6479B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FD06F6479B6B5FBA ON activity_log (account_id)
        SQL);
    }
}
