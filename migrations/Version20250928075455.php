<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250928075455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, information_system_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_7D3656A46E192A27 (information_system_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, account_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, function VARCHAR(255) NOT NULL, INDEX IDX_4C62E6389B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE account ADD CONSTRAINT FK_7D3656A46E192A27 FOREIGN KEY (information_system_id) REFERENCES information_system (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact ADD CONSTRAINT FK_4C62E6389B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE information_system ADD account_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE information_system ADD CONSTRAINT FK_4FFDE49B9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_4FFDE49B9B6B5FBA ON information_system (account_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE information_system DROP FOREIGN KEY FK_4FFDE49B9B6B5FBA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE account DROP FOREIGN KEY FK_7D3656A46E192A27
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6389B6B5FBA
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE account
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE contact
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_4FFDE49B9B6B5FBA ON information_system
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE information_system DROP account_id
        SQL);
    }
}
