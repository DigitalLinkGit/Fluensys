<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250622110109 extends AbstractMigration
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
            CREATE TABLE capture_element (id INT AUTO_INCREMENT NOT NULL, chapter_renderer_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_33ED8BFF1E745D3A (chapter_renderer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE capture_element_participant_role (capture_element_id INT NOT NULL, participant_role_id INT NOT NULL, INDEX IDX_1380BBDCDE152EAB (capture_element_id), INDEX IDX_1380BBDC4C0EEDA4 (participant_role_id), PRIMARY KEY(capture_element_id, participant_role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE chapter (id INT AUTO_INCREMENT NOT NULL, content_id INT NOT NULL, title VARCHAR(255) NOT NULL, level INT NOT NULL, UNIQUE INDEX UNIQ_F981B52E84A0A3ED (content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE chapter_content (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, format VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE chapter_renderer (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE field (id INT AUTO_INCREMENT NOT NULL, capture_element_id INT NOT NULL, label VARCHAR(255) NOT NULL, technical_name VARCHAR(255) NOT NULL, required TINYINT(1) NOT NULL, `index` INT NOT NULL, INDEX IDX_5BF54558DE152EAB (capture_element_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE participant_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, internal TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE calculated_variable ADD CONSTRAINT FK_76EE2621DE152EAB FOREIGN KEY (capture_element_id) REFERENCES capture_element (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD CONSTRAINT FK_33ED8BFF1E745D3A FOREIGN KEY (chapter_renderer_id) REFERENCES chapter_renderer (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element_participant_role ADD CONSTRAINT FK_1380BBDCDE152EAB FOREIGN KEY (capture_element_id) REFERENCES capture_element (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element_participant_role ADD CONSTRAINT FK_1380BBDC4C0EEDA4 FOREIGN KEY (participant_role_id) REFERENCES participant_role (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter ADD CONSTRAINT FK_F981B52E84A0A3ED FOREIGN KEY (content_id) REFERENCES chapter_content (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD CONSTRAINT FK_5BF54558DE152EAB FOREIGN KEY (capture_element_id) REFERENCES capture_element (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE calculated_variable DROP FOREIGN KEY FK_76EE2621DE152EAB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP FOREIGN KEY FK_33ED8BFF1E745D3A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element_participant_role DROP FOREIGN KEY FK_1380BBDCDE152EAB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element_participant_role DROP FOREIGN KEY FK_1380BBDC4C0EEDA4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52E84A0A3ED
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field DROP FOREIGN KEY FK_5BF54558DE152EAB
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE calculated_variable
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE capture_element
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE capture_element_participant_role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE chapter
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE chapter_content
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE chapter_renderer
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participant_role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
