<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251224214524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE listable_text_field (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE text_list_field (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE text_list_field_listable_text_field (text_list_field_id INT NOT NULL, listable_text_field_id INT NOT NULL, INDEX IDX_8513A8404674A921 (text_list_field_id), INDEX IDX_8513A840EBD24317 (listable_text_field_id), PRIMARY KEY(text_list_field_id, listable_text_field_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
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
            ALTER TABLE string_list_field DROP FOREIGN KEY FK_5BB24326BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE string_list_field_text_field DROP FOREIGN KEY FK_1B70F1C1A4BA315F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE string_list_field_text_field DROP FOREIGN KEY FK_1B70F1C1F2758BA2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE string_list_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE string_list_field_text_field
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE string_list_field (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE string_list_field_text_field (string_list_field_id INT NOT NULL, text_field_id INT NOT NULL, INDEX IDX_1B70F1C1A4BA315F (string_list_field_id), INDEX IDX_1B70F1C1F2758BA2 (text_field_id), PRIMARY KEY(string_list_field_id, text_field_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE string_list_field ADD CONSTRAINT FK_5BB24326BF396750 FOREIGN KEY (id) REFERENCES field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE string_list_field_text_field ADD CONSTRAINT FK_1B70F1C1A4BA315F FOREIGN KEY (string_list_field_id) REFERENCES string_list_field (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE string_list_field_text_field ADD CONSTRAINT FK_1B70F1C1F2758BA2 FOREIGN KEY (text_field_id) REFERENCES text_field (id) ON DELETE CASCADE
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
            DROP TABLE text_list_field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE text_list_field_listable_text_field
        SQL);
    }
}
