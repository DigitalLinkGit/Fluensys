<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251230153755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE listable_field (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE listable_field_text_item (id INT AUTO_INCREMENT NOT NULL, listable_field_id INT NOT NULL, value LONGTEXT DEFAULT NULL, INDEX IDX_F16BD479D938ADC9 (listable_field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE listable_field ADD CONSTRAINT FK_91AFDDBDBF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE listable_field_text_item ADD CONSTRAINT FK_F16BD479D938ADC9 FOREIGN KEY (listable_field_id) REFERENCES listable_field (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE system_component_capture_element DROP FOREIGN KEY FK_B8B00125BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE system_component_collection_field DROP FOREIGN KEY FK_7A809B4BBF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE system_component_collection_field_system_component DROP FOREIGN KEY FK_9DF2CC026583779A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE system_component_collection_field_system_component DROP FOREIGN KEY FK_9DF2CC02F19887F2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_list_field DROP FOREIGN KEY FK_1E9CD8C1BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_list_field_listable_text_field DROP FOREIGN KEY FK_8513A8404674A921
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_list_field_listable_text_field DROP FOREIGN KEY FK_8513A840EBD24317
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE listable_text_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE system_component_capture_element
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE system_component_collection_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE system_component_collection_field_system_component
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE text_list_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE text_list_field_listable_text_field
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE listable_text_field (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE system_component_capture_element (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE system_component_collection_field (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE system_component_collection_field_system_component (system_component_collection_field_id INT NOT NULL, system_component_id INT NOT NULL, INDEX IDX_9DF2CC026583779A (system_component_collection_field_id), INDEX IDX_9DF2CC02F19887F2 (system_component_id), PRIMARY KEY(system_component_collection_field_id, system_component_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE text_list_field (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE text_list_field_listable_text_field (text_list_field_id INT NOT NULL, listable_text_field_id INT NOT NULL, INDEX IDX_8513A8404674A921 (text_list_field_id), INDEX IDX_8513A840EBD24317 (listable_text_field_id), PRIMARY KEY(text_list_field_id, listable_text_field_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE system_component_capture_element ADD CONSTRAINT FK_B8B00125BF396750 FOREIGN KEY (id) REFERENCES capture_element (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE system_component_collection_field ADD CONSTRAINT FK_7A809B4BBF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE system_component_collection_field_system_component ADD CONSTRAINT FK_9DF2CC026583779A FOREIGN KEY (system_component_collection_field_id) REFERENCES system_component_collection_field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE system_component_collection_field_system_component ADD CONSTRAINT FK_9DF2CC02F19887F2 FOREIGN KEY (system_component_id) REFERENCES system_component (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_list_field ADD CONSTRAINT FK_1E9CD8C1BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_list_field_listable_text_field ADD CONSTRAINT FK_8513A8404674A921 FOREIGN KEY (text_list_field_id) REFERENCES text_list_field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_list_field_listable_text_field ADD CONSTRAINT FK_8513A840EBD24317 FOREIGN KEY (listable_text_field_id) REFERENCES listable_text_field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE listable_field DROP FOREIGN KEY FK_91AFDDBDBF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE listable_field_text_item DROP FOREIGN KEY FK_F16BD479D938ADC9
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE listable_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE listable_field_text_item
        SQL);
    }
}
