<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250831125011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE participant_assignment (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, role_id INT NOT NULL, external_last_name VARCHAR(255) DEFAULT NULL, external_first_name VARCHAR(255) DEFAULT NULL, external_email VARCHAR(255) DEFAULT NULL, external_function VARCHAR(255) DEFAULT NULL, INDEX IDX_C05A2A4166D1F9C (project_id), INDEX IDX_C05A2A4D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment ADD CONSTRAINT FK_C05A2A4166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment ADD CONSTRAINT FK_C05A2A4D60322AC FOREIGN KEY (role_id) REFERENCES participant_role (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment DROP FOREIGN KEY FK_C05A2A4166D1F9C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant_assignment DROP FOREIGN KEY FK_C05A2A4D60322AC
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participant_assignment
        SQL);
    }
}
