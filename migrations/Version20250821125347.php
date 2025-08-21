<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250821125347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE calculated_variable (id INT AUTO_INCREMENT NOT NULL, capture_element_id INT NOT NULL, name VARCHAR(255) NOT NULL, technical_name VARCHAR(255) NOT NULL, expression VARCHAR(255) NOT NULL, INDEX IDX_76EE2621DE152EAB (capture_element_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE capture_element (id INT AUTO_INCREMENT NOT NULL, respondent_id INT NOT NULL, responsible_id INT NOT NULL, validator_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_33ED8BFFCE80CD19 (respondent_id), INDEX IDX_33ED8BFF602AD315 (responsible_id), INDEX IDX_33ED8BFFB0644AEC (validator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE chapter (id INT AUTO_INCREMENT NOT NULL, content_id INT NOT NULL, title VARCHAR(255) NOT NULL, level INT NOT NULL, UNIQUE INDEX UNIQ_F981B52E84A0A3ED (content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE chapter_content (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, format VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE date_field (id INT NOT NULL, value DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE decimal_field (id INT NOT NULL, value NUMERIC(10, 2) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE field (id INT AUTO_INCREMENT NOT NULL, capture_element_id INT NOT NULL, external_label VARCHAR(255) NOT NULL, internal_label VARCHAR(255) NOT NULL, technical_name VARCHAR(255) NOT NULL, required TINYINT(1) NOT NULL, position INT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_5BF54558DE152EAB (capture_element_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE flex_capture (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE integer_field (id INT NOT NULL, value INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE participant_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, internal TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE text_area_field (id INT NOT NULL, value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE text_field (id INT NOT NULL, value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE calculated_variable ADD CONSTRAINT FK_76EE2621DE152EAB FOREIGN KEY (capture_element_id) REFERENCES capture_element (id)
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
            ALTER TABLE chapter ADD CONSTRAINT FK_F981B52E84A0A3ED FOREIGN KEY (content_id) REFERENCES chapter_content (id)
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
            ALTER TABLE flex_capture ADD CONSTRAINT FK_553D70BF396750 FOREIGN KEY (id) REFERENCES capture_element (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE integer_field ADD CONSTRAINT FK_85B54CB0BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_area_field ADD CONSTRAINT FK_4AC50418BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_field ADD CONSTRAINT FK_D41FF05BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE calculated_variable DROP FOREIGN KEY FK_76EE2621DE152EAB
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
            ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52E84A0A3ED
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
            ALTER TABLE flex_capture DROP FOREIGN KEY FK_553D70BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE integer_field DROP FOREIGN KEY FK_85B54CB0BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_area_field DROP FOREIGN KEY FK_4AC50418BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE text_field DROP FOREIGN KEY FK_D41FF05BF396750
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE calculated_variable
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE capture_element
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE chapter
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE chapter_content
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
            DROP TABLE flex_capture
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE integer_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participant_role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE text_area_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE text_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
