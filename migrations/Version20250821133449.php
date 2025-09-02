<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250821133449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE capture (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, template TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE capture_capture_element (capture_id INT NOT NULL, capture_element_id INT NOT NULL, INDEX IDX_504F58326B301384 (capture_id), INDEX IDX_504F5832DE152EAB (capture_element_id), PRIMARY KEY(capture_id, capture_element_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_capture_element ADD CONSTRAINT FK_504F58326B301384 FOREIGN KEY (capture_id) REFERENCES capture (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_capture_element ADD CONSTRAINT FK_504F5832DE152EAB FOREIGN KEY (capture_element_id) REFERENCES capture_element (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_capture_element DROP FOREIGN KEY FK_504F58326B301384
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_capture_element DROP FOREIGN KEY FK_504F5832DE152EAB
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE capture
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE capture_capture_element
        SQL);
    }
}
