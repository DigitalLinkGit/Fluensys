<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260111121511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE project ADD responsible_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE602AD315 FOREIGN KEY (responsible_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2FB3D0EE602AD315 ON project (responsible_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE602AD315
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2FB3D0EE602AD315 ON project
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE project DROP responsible_id
        SQL);
    }
}
