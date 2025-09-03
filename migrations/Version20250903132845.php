<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250903132845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE checkbox_list_field DROP FOREIGN KEY FK_59CF166BF396750
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE checkbox_list_field
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE checkbox_list_field (id INT NOT NULL, value JSON DEFAULT NULL COMMENT '(DC2Type:json)', choices JSON NOT NULL COMMENT '(DC2Type:json)', min_selections INT DEFAULT NULL, max_selections INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE checkbox_list_field ADD CONSTRAINT FK_59CF166BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
    }
}
