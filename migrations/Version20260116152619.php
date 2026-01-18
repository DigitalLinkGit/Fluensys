<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260116152619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE table_field (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE table_field_column (id INT AUTO_INCREMENT NOT NULL, table_field_id INT NOT NULL, col_key VARCHAR(80) NOT NULL, label VARCHAR(255) NOT NULL, type VARCHAR(20) NOT NULL, position INT NOT NULL, INDEX IDX_E2F49ABED48A2960 (table_field_id), UNIQUE INDEX uniq_table_field_column_key (table_field_id, col_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE table_field_row (id INT AUTO_INCREMENT NOT NULL, table_field_id INT NOT NULL, position INT NOT NULL, `values` JSON DEFAULT NULL COMMENT '(DC2Type:json)', INDEX IDX_A2827539D48A2960 (table_field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE table_field ADD CONSTRAINT FK_57098820BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE table_field_column ADD CONSTRAINT FK_E2F49ABED48A2960 FOREIGN KEY (table_field_id) REFERENCES table_field (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE table_field_row ADD CONSTRAINT FK_A2827539D48A2960 FOREIGN KEY (table_field_id) REFERENCES table_field (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE table_field DROP FOREIGN KEY FK_57098820BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE table_field_column DROP FOREIGN KEY FK_E2F49ABED48A2960
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE table_field_row DROP FOREIGN KEY FK_A2827539D48A2960
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE table_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE table_field_column
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE table_field_row
        SQL);
    }
}
