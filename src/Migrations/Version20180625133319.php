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

/**
 * DEV/PROD DATABASE.
 *
 * Migration to remove unused tables
 */
final class Version20180625133319 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mails_sent DROP FOREIGN KEY FK_A34C3D90C8776F01');
        $this->addSql('ALTER TABLE orbitale_cms_categories DROP FOREIGN KEY FK_A8EF7232727ACA70');
        $this->addSql('ALTER TABLE orbitale_cms_pages DROP FOREIGN KEY FK_C0E694ED12469DE2');
        $this->addSql('ALTER TABLE orbitale_cms_pages DROP FOREIGN KEY FK_C0E694ED727ACA70');
        $this->addSql('DROP TABLE mails');
        $this->addSql('DROP TABLE mails_sent');
        $this->addSql('DROP TABLE orbitale_cms_categories');
        $this->addSql('DROP TABLE orbitale_cms_pages');
        $this->addSql('DROP TABLE regions');
    }

    public function down(Schema $schema): void
    {
    }
}
