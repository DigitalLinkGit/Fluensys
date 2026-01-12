<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260112093425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Idempotent add project_id + FK + index on participant_assignment';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        // 1) Column project_id
        $columns = $sm->listTableColumns('participant_assignment');
        if (!isset($columns['project_id'])) {
            $this->addSql('ALTER TABLE participant_assignment ADD project_id INT DEFAULT NULL');
        } else {
            // Ensure nullable (in case it was created NOT NULL during a failed attempt)
            $this->addSql('ALTER TABLE participant_assignment MODIFY project_id INT DEFAULT NULL');
        }

        // 2) Index
        $indexes = $sm->listTableIndexes('participant_assignment');
        if (!isset($indexes['idx_c05a2a4166d1f9c'])) {
            $this->addSql('CREATE INDEX IDX_C05A2A4166D1F9C ON participant_assignment (project_id)');
        }

        // 3) Foreign key
        $fks = $sm->listTableForeignKeys('participant_assignment');
        $fkExists = false;
        foreach ($fks as $fk) {
            if ($fk->getName() === 'FK_C05A2A4166D1F9C') {
                $fkExists = true;
                break;
            }
        }

        if (!$fkExists) {
            $this->addSql(
                'ALTER TABLE participant_assignment ' .
                'ADD CONSTRAINT FK_C05A2A4166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE'
            );
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        // Drop FK if exists
        $fks = $sm->listTableForeignKeys('participant_assignment');
        foreach ($fks as $fk) {
            if ($fk->getName() === 'FK_C05A2A4166D1F9C') {
                $this->addSql('ALTER TABLE participant_assignment DROP FOREIGN KEY FK_C05A2A4166D1F9C');
                break;
            }
        }

        // Drop index if exists
        $indexes = $sm->listTableIndexes('participant_assignment');
        if (isset($indexes['idx_c05a2a4166d1f9c'])) {
            $this->addSql('DROP INDEX IDX_C05A2A4166D1F9C ON participant_assignment');
        }

        // Drop column if exists
        $columns = $sm->listTableColumns('participant_assignment');
        if (isset($columns['project_id'])) {
            $this->addSql('ALTER TABLE participant_assignment DROP COLUMN project_id');
        }
    }
}
