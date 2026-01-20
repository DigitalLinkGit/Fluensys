<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260120121842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log ADD account_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F6479B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FD06F6479B6B5FBA ON activity_log (account_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F6479B6B5FBA
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_FD06F6479B6B5FBA ON activity_log
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log DROP account_id
        SQL);
    }
}
