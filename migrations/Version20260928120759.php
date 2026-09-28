<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260928120759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE task SET status = 'to_do' WHERE status = 'to do'");
        $this->addSql("UPDATE task SET status = 'in_progress' WHERE status = 'doing'");
        $this->addSql("UPDATE project SET status = 'in_progress' WHERE status = 'en cours'");
        $this->addSql("UPDATE project SET status = 'archived' WHERE status = 'archivé'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("UPDATE task SET status = 'to do' WHERE status = 'to_do'");
        $this->addSql("UPDATE task SET status = 'doing' WHERE status = 'in_progress'");
        $this->addSql("UPDATE project SET status = 'en cours' WHERE status = 'in_progress'");
        $this->addSql("UPDATE project SET status = 'archivé' WHERE status = 'archived'");
    }
}
