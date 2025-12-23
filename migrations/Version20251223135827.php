<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223135827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE email_field (id INT NOT NULL, value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE url_field (id INT NOT NULL, value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE email_field ADD CONSTRAINT FK_C6B804D7BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE url_field ADD CONSTRAINT FK_9E512A2FBF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE email_field DROP FOREIGN KEY FK_C6B804D7BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE url_field DROP FOREIGN KEY FK_9E512A2FBF396750
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE email_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE url_field
        SQL);
    }
}
