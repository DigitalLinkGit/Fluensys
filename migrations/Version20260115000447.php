<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260115000447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant ADD rendering_config_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant ADD CONSTRAINT FK_4E59C462CB6D06F4 FOREIGN KEY (rendering_config_id) REFERENCES rendering_config (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_4E59C462CB6D06F4 ON tenant (rendering_config_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant DROP FOREIGN KEY FK_4E59C462CB6D06F4
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_4E59C462CB6D06F4 ON tenant
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tenant DROP rendering_config_id
        SQL);
    }
}
