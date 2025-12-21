<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251221124726 extends AbstractMigration
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
            CREATE TABLE calculated_variable (id INT AUTO_INCREMENT NOT NULL, capture_element_id INT NOT NULL, name VARCHAR(255) NOT NULL, technical_name VARCHAR(255) NOT NULL, expression VARCHAR(255) NOT NULL, INDEX IDX_76EE2621DE152EAB (capture_element_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE capture (id INT AUTO_INCREMENT NOT NULL, title_id INT DEFAULT NULL, account_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, template TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8BFEA6E5A9F87BD (title_id), INDEX IDX_8BFEA6E59B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE capture_condition (id INT AUTO_INCREMENT NOT NULL, source_element_id INT NOT NULL, target_element_id INT NOT NULL, source_field_id INT NOT NULL, capture_id INT NOT NULL, expected_value VARCHAR(255) NOT NULL, INDEX IDX_D6CFAC7D63AB5B00 (source_element_id), INDEX IDX_D6CFAC7D2DF3F2B5 (target_element_id), INDEX IDX_D6CFAC7D7173162 (source_field_id), INDEX IDX_D6CFAC7D6B301384 (capture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE capture_element (id INT AUTO_INCREMENT NOT NULL, respondent_id INT NOT NULL, responsible_id INT NOT NULL, validator_id INT NOT NULL, chapter_id INT DEFAULT NULL, capture_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, template TINYINT(1) DEFAULT 1 NOT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, position INT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_33ED8BFFCE80CD19 (respondent_id), INDEX IDX_33ED8BFF602AD315 (responsible_id), INDEX IDX_33ED8BFFB0644AEC (validator_id), UNIQUE INDEX UNIQ_33ED8BFF579F4768 (chapter_id), INDEX IDX_33ED8BFF6B301384 (capture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE chapter (id INT AUTO_INCREMENT NOT NULL, title_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F981B52EA9F87BD (title_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE checklist_field (id INT NOT NULL, choices JSON NOT NULL COMMENT '(DC2Type:json)', value JSON DEFAULT NULL COMMENT '(DC2Type:json)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, account_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, function VARCHAR(255) NOT NULL, INDEX IDX_4C62E6389B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE date_field (id INT NOT NULL, value DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE decimal_field (id INT NOT NULL, value NUMERIC(14, 4) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE field (id INT AUTO_INCREMENT NOT NULL, capture_element_id INT NOT NULL, external_config_id INT DEFAULT NULL, internal_config_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, technical_name VARCHAR(255) NOT NULL, position INT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_5BF54558DE152EAB (capture_element_id), UNIQUE INDEX UNIQ_5BF5455880A997 (external_config_id), UNIQUE INDEX UNIQ_5BF54558AC1CFEE8 (internal_config_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE field_config (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, help VARCHAR(255) DEFAULT NULL, required TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE flex_capture_element (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE information_system (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE integer_field (id INT NOT NULL, value INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE participant_assignment (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, external_last_name VARCHAR(255) DEFAULT NULL, external_first_name VARCHAR(255) DEFAULT NULL, external_email VARCHAR(255) DEFAULT NULL, external_function VARCHAR(255) DEFAULT NULL, INDEX IDX_C05A2A4D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE participant_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, internal TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE system_component (id INT AUTO_INCREMENT NOT NULL, information_system_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_4A21A9EA6E192A27 (information_system_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE system_component_capture_element (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE system_component_collection_field (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE system_component_collection_field_system_component (system_component_collection_field_id INT NOT NULL, system_component_id INT NOT NULL, INDEX IDX_9DF2CC026583779A (system_component_collection_field_id), INDEX IDX_9DF2CC02F19887F2 (system_component_id), PRIMARY KEY(system_component_collection_field_id, system_component_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE text_area_field (id INT NOT NULL, value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE text_chapter (id INT NOT NULL, content VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE text_field (id INT NOT NULL, value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE title (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(255) DEFAULT NULL, level INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE account ADD CONSTRAINT FK_7D3656A46E192A27 FOREIGN KEY (information_system_id) REFERENCES information_system (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE calculated_variable ADD CONSTRAINT FK_76EE2621DE152EAB FOREIGN KEY (capture_element_id) REFERENCES capture_element (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture ADD CONSTRAINT FK_8BFEA6E5A9F87BD FOREIGN KEY (title_id) REFERENCES title (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture ADD CONSTRAINT FK_8BFEA6E59B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_condition ADD CONSTRAINT FK_D6CFAC7D63AB5B00 FOREIGN KEY (source_element_id) REFERENCES capture_element (id) ON DELETE RESTRICT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_condition ADD CONSTRAINT FK_D6CFAC7D2DF3F2B5 FOREIGN KEY (target_element_id) REFERENCES capture_element (id) ON DELETE RESTRICT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_condition ADD CONSTRAINT FK_D6CFAC7D7173162 FOREIGN KEY (source_field_id) REFERENCES field (id) ON DELETE RESTRICT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_condition ADD CONSTRAINT FK_D6CFAC7D6B301384 FOREIGN KEY (capture_id) REFERENCES capture (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD CONSTRAINT FK_33ED8BFFCE80CD19 FOREIGN KEY (respondent_id) REFERENCES participant_role (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD CONSTRAINT FK_33ED8BFF602AD315 FOREIGN KEY (responsible_id) REFERENCES participant_role (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD CONSTRAINT FK_33ED8BFFB0644AEC FOREIGN KEY (validator_id) REFERENCES participant_role (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD CONSTRAINT FK_33ED8BFF579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD CONSTRAINT FK_33ED8BFF6B301384 FOREIGN KEY (capture_id) REFERENCES capture (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter ADD CONSTRAINT FK_F981B52EA9F87BD FOREIGN KEY (title_id) REFERENCES title (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE checklist_field ADD CONSTRAINT FK_651DC286BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact ADD CONSTRAINT FK_4C62E6389B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE date_field ADD CONSTRAINT FK_E105ADD4BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE decimal_field ADD CONSTRAINT FK_87FDAF1BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD CONSTRAINT FK_5BF54558DE152EAB FOREIGN KEY (capture_element_id) REFERENCES capture_element (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD CONSTRAINT FK_5BF5455880A997 FOREIGN KEY (external_config_id) REFERENCES field_config (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD CONSTRAINT FK_5BF54558AC1CFEE8 FOREIGN KEY (internal_config_id) REFERENCES field_config (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flex_capture_element ADD CONSTRAINT FK_CAF7302BF396750 FOREIGN KEY (id) REFERENCES capture_element (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE integer_field ADD CONSTRAINT FK_85B54CB0BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment ADD CONSTRAINT FK_C05A2A4D60322AC FOREIGN KEY (role_id) REFERENCES participant_role (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE system_component ADD CONSTRAINT FK_4A21A9EA6E192A27 FOREIGN KEY (information_system_id) REFERENCES information_system (id)
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
            ALTER TABLE text_area_field ADD CONSTRAINT FK_4AC50418BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_chapter ADD CONSTRAINT FK_DD209A11BF396750 FOREIGN KEY (id) REFERENCES chapter (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_field ADD CONSTRAINT FK_D41FF05BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE account DROP FOREIGN KEY FK_7D3656A46E192A27
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE calculated_variable DROP FOREIGN KEY FK_76EE2621DE152EAB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture DROP FOREIGN KEY FK_8BFEA6E5A9F87BD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture DROP FOREIGN KEY FK_8BFEA6E59B6B5FBA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_condition DROP FOREIGN KEY FK_D6CFAC7D63AB5B00
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_condition DROP FOREIGN KEY FK_D6CFAC7D2DF3F2B5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_condition DROP FOREIGN KEY FK_D6CFAC7D7173162
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_condition DROP FOREIGN KEY FK_D6CFAC7D6B301384
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP FOREIGN KEY FK_33ED8BFFCE80CD19
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP FOREIGN KEY FK_33ED8BFF602AD315
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP FOREIGN KEY FK_33ED8BFFB0644AEC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP FOREIGN KEY FK_33ED8BFF579F4768
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP FOREIGN KEY FK_33ED8BFF6B301384
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52EA9F87BD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE checklist_field DROP FOREIGN KEY FK_651DC286BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6389B6B5FBA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE date_field DROP FOREIGN KEY FK_E105ADD4BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE decimal_field DROP FOREIGN KEY FK_87FDAF1BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field DROP FOREIGN KEY FK_5BF54558DE152EAB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field DROP FOREIGN KEY FK_5BF5455880A997
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field DROP FOREIGN KEY FK_5BF54558AC1CFEE8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE flex_capture_element DROP FOREIGN KEY FK_CAF7302BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE integer_field DROP FOREIGN KEY FK_85B54CB0BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment DROP FOREIGN KEY FK_C05A2A4D60322AC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE system_component DROP FOREIGN KEY FK_4A21A9EA6E192A27
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
            ALTER TABLE text_area_field DROP FOREIGN KEY FK_4AC50418BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_chapter DROP FOREIGN KEY FK_DD209A11BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_field DROP FOREIGN KEY FK_D41FF05BF396750
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE account
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE calculated_variable
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE capture
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE capture_condition
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE capture_element
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE chapter
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE checklist_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE contact
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE date_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE decimal_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE field_config
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE flex_capture_element
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE information_system
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE integer_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participant_assignment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participant_role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE system_component
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
            DROP TABLE text_area_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE text_chapter
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE text_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE title
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
