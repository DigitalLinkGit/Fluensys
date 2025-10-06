<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251006083210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE system_component_collection_field_system_component (system_component_collection_field_id INT NOT NULL, system_component_id INT NOT NULL, INDEX IDX_9DF2CC026583779A (system_component_collection_field_id), INDEX IDX_9DF2CC02F19887F2 (system_component_id), PRIMARY KEY(system_component_collection_field_id, system_component_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE system_component_collection_field_system_component ADD CONSTRAINT FK_9DF2CC026583779A FOREIGN KEY (system_component_collection_field_id) REFERENCES system_component_collection_field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE system_component_collection_field_system_component ADD CONSTRAINT FK_9DF2CC02F19887F2 FOREIGN KEY (system_component_id) REFERENCES system_component (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE system_component_collection_field_system_component DROP FOREIGN KEY FK_9DF2CC026583779A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE system_component_collection_field_system_component DROP FOREIGN KEY FK_9DF2CC02F19887F2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE system_component_collection_field_system_component
        SQL);
    }
}
