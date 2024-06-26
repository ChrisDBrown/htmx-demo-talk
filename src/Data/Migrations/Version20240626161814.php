<?php

declare(strict_types=1);

namespace App\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240626161814 extends AbstractMigration
{
    #[\Override]
    public function getDescription(): string
    {
        return 'No idea what this is complaining about tbh, maybe DateTimeImmutable?';
    }

    #[\Override]
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, roles, last_read_offset, created_at FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id BLOB NOT NULL --(DC2Type:uuid)
        , username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , last_read_offset INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO user (id, username, roles, last_read_offset, created_at) SELECT id, username, roles, last_read_offset, created_at FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user (username)');
    }

    #[\Override]
    public function down(Schema $schema): void
    {
        // No down migration should be added, follow expand-contract practices
    }
}
