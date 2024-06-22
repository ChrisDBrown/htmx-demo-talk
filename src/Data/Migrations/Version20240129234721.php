<?php

declare(strict_types=1);

namespace App\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240129234721 extends AbstractMigration
{
    #[\Override]
    public function getDescription(): string
    {
        return 'Set up profile table';
    }

    #[\Override]
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE profile (id BLOB NOT NULL --(DC2Type:uuid)
        , name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id))');
    }

    #[\Override]
    public function down(Schema $schema): void
    {
        // No down migration should be added, follow expand-contract practices
    }
}
