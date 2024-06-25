<?php

declare(strict_types=1);

namespace App\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240624210606 extends AbstractMigration
{
    #[\Override]
    public function getDescription(): string
    {
        return 'Add basic user entity and drop profile placeholder';
    }

    #[\Override]
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user (id BLOB NOT NULL --(DC2Type:uuid)
        , username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user (username)');
        $this->addSql('DROP TABLE profile');
    }

    #[\Override]
    public function down(Schema $schema): void
    {
        // No down migration should be added, follow expand-contract practices
    }
}
