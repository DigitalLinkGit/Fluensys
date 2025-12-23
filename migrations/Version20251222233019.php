<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251222233019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE capture ADD responsible_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture ADD CONSTRAINT FK_8BFEA6E5602AD315 FOREIGN KEY (responsible_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8BFEA6E5602AD315 ON capture (responsible_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP FOREIGN KEY FK_33ED8BFF602AD315
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP FOREIGN KEY FK_33ED8BFFCE80CD19
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_33ED8BFFCE80CD19 ON capture_element
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_33ED8BFF602AD315 ON capture_element
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD contributor_id INT DEFAULT NULL, DROP respondent_id, DROP responsible_id, CHANGE validator_id validator_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD CONSTRAINT FK_33ED8BFF7A19A357 FOREIGN KEY (contributor_id) REFERENCES participant_role (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_33ED8BFF7A19A357 ON capture_element (contributor_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE capture DROP FOREIGN KEY FK_8BFEA6E5602AD315
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8BFEA6E5602AD315 ON capture
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture DROP responsible_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element DROP FOREIGN KEY FK_33ED8BFF7A19A357
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_33ED8BFF7A19A357 ON capture_element
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD respondent_id INT NOT NULL, ADD responsible_id INT NOT NULL, DROP contributor_id, CHANGE validator_id validator_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD CONSTRAINT FK_33ED8BFF602AD315 FOREIGN KEY (responsible_id) REFERENCES participant_role (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capture_element ADD CONSTRAINT FK_33ED8BFFCE80CD19 FOREIGN KEY (respondent_id) REFERENCES participant_role (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_33ED8BFFCE80CD19 ON capture_element (respondent_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_33ED8BFF602AD315 ON capture_element (responsible_id)
        SQL);
    }
}
