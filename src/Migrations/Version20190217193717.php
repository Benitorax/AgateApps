<?php

declare(strict_types=1);

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190217193717 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE characters
            CHANGE sex sex VARCHAR(30) NOT NULL,
            CHANGE treasures treasures LONGTEXT DEFAULT NULL COMMENT "(DC2Type:simple_array)",
            CHANGE orientation orientation VARCHAR(40) NOT NULL
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on "mysql".');

        $this->addSql('ALTER TABLE characters
            CHANGE sex sex VARCHAR(1) NOT NULL COLLATE utf8_unicode_ci,
            CHANGE treasures treasures LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT "(DC2Type:simple_array)",
            CHANGE orientation orientation VARCHAR(30) NOT NULL COLLATE utf8_unicode_ci
        ');
    }
}
