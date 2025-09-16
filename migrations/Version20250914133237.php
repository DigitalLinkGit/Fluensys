<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250914133237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` ADD capture_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` ADD CONSTRAINT FK_BDD688436B301384 FOREIGN KEY (capture_id) REFERENCES capture (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BDD688436B301384 ON `condition` (capture_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` DROP FOREIGN KEY FK_BDD688436B301384
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_BDD688436B301384 ON `condition`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `condition` DROP capture_id
        SQL);
    }
}
