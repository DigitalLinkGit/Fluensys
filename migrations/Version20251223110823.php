<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223110823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE field DROP FOREIGN KEY FK_5BF5455880A997
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field DROP FOREIGN KEY FK_5BF54558AC1CFEE8
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE field_config
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_5BF54558AC1CFEE8 ON field
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_5BF5455880A997 ON field
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD label VARCHAR(255) NOT NULL, ADD help VARCHAR(255) DEFAULT NULL, ADD required TINYINT(1) NOT NULL, DROP external_config_id, DROP internal_config_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE field_config (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, help VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, required TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD external_config_id INT DEFAULT NULL, ADD internal_config_id INT DEFAULT NULL, DROP label, DROP help, DROP required
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD CONSTRAINT FK_5BF5455880A997 FOREIGN KEY (external_config_id) REFERENCES field_config (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD CONSTRAINT FK_5BF54558AC1CFEE8 FOREIGN KEY (internal_config_id) REFERENCES field_config (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_5BF54558AC1CFEE8 ON field (internal_config_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_5BF5455880A997 ON field (external_config_id)
        SQL);
    }
}
