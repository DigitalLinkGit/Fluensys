<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251230125658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE listable_field_capture_element DROP FOREIGN KEY FK_7F1995C443707B0
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_7F1995C443707B0 ON listable_field_capture_element
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE listable_field_capture_element DROP field_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE listable_field_capture_element ADD field_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE listable_field_capture_element ADD CONSTRAINT FK_7F1995C443707B0 FOREIGN KEY (field_id) REFERENCES field (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_7F1995C443707B0 ON listable_field_capture_element (field_id)
        SQL);
    }
}
