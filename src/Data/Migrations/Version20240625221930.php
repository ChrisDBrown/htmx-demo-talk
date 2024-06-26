<?php

declare(strict_types=1);

namespace App\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240625221930 extends AbstractMigration
{
    #[\Override]
    public function getDescription(): string
    {
        return 'Add messages and reactions for feed';
    }

    #[\Override]
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE message (id BLOB NOT NULL --(DC2Type:uuid)
        , content CLOB NOT NULL, "offset" INTEGER NOT NULL, username VARCHAR(180) NOT NULL, user_id BLOB DEFAULT NULL --(DC2Type:uuid)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE react (id BLOB NOT NULL --(DC2Type:uuid)
        , type VARCHAR(255) NOT NULL, "offset" INTEGER NOT NULL, username VARCHAR(180) NOT NULL, user_id BLOB DEFAULT NULL --(DC2Type:uuid)
        , PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE user ADD COLUMN last_read_offset INTEGER NOT NULL');
        $this->addSql('ALTER TABLE user ADD COLUMN created_at DATETIME NOT NULL');
    }

    #[\Override]
    public function down(Schema $schema): void
    {
        // No down migration should be added, follow expand-contract practices
    }
}
