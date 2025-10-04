<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251003120145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD name VARCHAR(255) NOT NULL, ADD external_required TINYINT(1) NOT NULL, CHANGE required internal_required TINYINT(1) NOT NULL, CHANGE position internal_position INT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD required TINYINT(1) NOT NULL, DROP name, DROP internal_required, DROP external_required, CHANGE internal_position position INT NOT NULL
        SQL);
    }
}
