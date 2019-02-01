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

final class Version20190201192009 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            ALTER TABLE setbacks
            ADD is_unlucky TINYINT(1) DEFAULT \'0\' NOT NULL,
            ADD is_lucky TINYINT(1) DEFAULT \'0\' NOT NULL,
            CHANGE malus malus VARCHAR(50) DEFAULT \'\' NOT NULL;
        ');

        $this->addSql('UPDATE setbacks SET is_lucky = 1 WHERE name = "Chance";');
        $this->addSql('UPDATE setbacks SET is_unlucky = 1 WHERE name = "Poisse";');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE setbacks DROP is_unlucky, DROP is_lucky, CHANGE malus malus VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci');
    }
}
