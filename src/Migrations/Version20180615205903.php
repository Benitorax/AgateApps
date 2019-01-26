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

use CorahnRin\Data\Ways;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180615205903 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE disorders_ways DROP FOREIGN KEY FK_F2628E178C803113');
        $this->addSql('ALTER TABLE domains DROP FOREIGN KEY FK_8C7BBF9D8C803113');
        $this->addSql('ALTER TABLE traits DROP FOREIGN KEY FK_E4A0A1668C803113');
        $this->addSql('DROP TABLE characters_status');
        $this->addSql('DROP TABLE characters_ways');
        $this->addSql('DROP TABLE ways');
        $this->addSql('
          ALTER TABLE characters
          ADD temporary_trauma SMALLINT DEFAULT 0 NOT NULL,
          ADD permanent_trauma SMALLINT DEFAULT 0 NOT NULL,
          ADD mental_resistance_bonus SMALLINT NOT NULL,
          ADD combativeness INT NOT NULL,
          ADD creativity INT NOT NULL,
          ADD empathy INT NOT NULL,
          ADD reason INT NOT NULL,
          ADD conviction INT NOT NULL,
          DROP status,
          DROP trauma,
          DROP trauma_permanent,
          DROP mental_resist,
          DROP mental_resist_bonus
        ');
        $this->addSql('DROP INDEX IDX_F2628E178C803113 ON disorders_ways');
        $this->addSql('ALTER TABLE disorders_ways DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE disorders_ways ADD way VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX IDX_8C7BBF9D8C803113 ON domains');
        $this->addSql('ALTER TABLE domains ADD way VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX IDX_E4A0A1668C803113 ON traits');
        $this->addSql('DROP INDEX idxUnique ON traits');
        $this->addSql('ALTER TABLE traits ADD way VARCHAR(255) NOT NULL');

        $this->addSql('UPDATE disorders_ways SET way = :way WHERE way_id = :way_id', ['way' => Ways::COMBATIVENESS, 'way_id' => 1]);
        $this->addSql('UPDATE disorders_ways SET way = :way WHERE way_id = :way_id', ['way' => Ways::CREATIVITY, 'way_id' => 2]);
        $this->addSql('UPDATE disorders_ways SET way = :way WHERE way_id = :way_id', ['way' => Ways::EMPATHY, 'way_id' => 3]);
        $this->addSql('UPDATE disorders_ways SET way = :way WHERE way_id = :way_id', ['way' => Ways::REASON, 'way_id' => 4]);
        $this->addSql('UPDATE disorders_ways SET way = :way WHERE way_id = :way_id', ['way' => Ways::CONVICTION, 'way_id' => 5]);

        $this->addSql('UPDATE domains SET way = :way WHERE way_id = :way_id', ['way' => Ways::COMBATIVENESS, 'way_id' => 1]);
        $this->addSql('UPDATE domains SET way = :way WHERE way_id = :way_id', ['way' => Ways::CREATIVITY, 'way_id' => 2]);
        $this->addSql('UPDATE domains SET way = :way WHERE way_id = :way_id', ['way' => Ways::EMPATHY, 'way_id' => 3]);
        $this->addSql('UPDATE domains SET way = :way WHERE way_id = :way_id', ['way' => Ways::REASON, 'way_id' => 4]);
        $this->addSql('UPDATE domains SET way = :way WHERE way_id = :way_id', ['way' => Ways::CONVICTION, 'way_id' => 5]);

        $this->addSql('UPDATE traits SET way = :way WHERE way_id = :way_id', ['way' => Ways::COMBATIVENESS, 'way_id' => 1]);
        $this->addSql('UPDATE traits SET way = :way WHERE way_id = :way_id', ['way' => Ways::CREATIVITY, 'way_id' => 2]);
        $this->addSql('UPDATE traits SET way = :way WHERE way_id = :way_id', ['way' => Ways::EMPATHY, 'way_id' => 3]);
        $this->addSql('UPDATE traits SET way = :way WHERE way_id = :way_id', ['way' => Ways::REASON, 'way_id' => 4]);
        $this->addSql('UPDATE traits SET way = :way WHERE way_id = :way_id', ['way' => Ways::CONVICTION, 'way_id' => 5]);

        $this->addSql('ALTER TABLE disorders_ways ADD PRIMARY KEY (disorder_id, way)');

        $this->addSql('ALTER TABLE disorders_ways DROP way_id');
        $this->addSql('ALTER TABLE domains DROP way_id');
        $this->addSql('ALTER TABLE traits DROP way_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE characters_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE characters_ways (character_id INT NOT NULL, way_id INT NOT NULL, score INT NOT NULL, INDEX IDX_7AC056231136BE75 (character_id), INDEX IDX_7AC056238C803113 (way_id), PRIMARY KEY(character_id, way_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ways (id INT AUTO_INCREMENT NOT NULL, short_name VARCHAR(3) NOT NULL COLLATE utf8_unicode_ci, name VARCHAR(40) NOT NULL COLLATE utf8_unicode_ci, fault VARCHAR(40) NOT NULL COLLATE utf8_unicode_ci, description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_A94804173EE4B093 (short_name), UNIQUE INDEX UNIQ_A94804175E237E06 (name), UNIQUE INDEX UNIQ_A94804179FD0DEA3 (fault), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE characters_ways ADD CONSTRAINT FK_7AC056231136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_ways ADD CONSTRAINT FK_7AC056238C803113 FOREIGN KEY (way_id) REFERENCES ways (id)');
        $this->addSql('ALTER TABLE characters ADD status SMALLINT DEFAULT 0 NOT NULL, ADD trauma SMALLINT DEFAULT 0 NOT NULL, ADD trauma_permanent SMALLINT DEFAULT 0 NOT NULL, ADD mental_resist_bonus SMALLINT NOT NULL, DROP temporary_trauma, DROP permanent_trauma, DROP combativeness, DROP creativity, DROP empathy, DROP reason, DROP conviction, CHANGE mental_resistance_bonus mental_resist SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE disorders_ways DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE disorders_ways ADD way_id INT NOT NULL, DROP way');
        $this->addSql('ALTER TABLE disorders_ways ADD CONSTRAINT FK_F2628E178C803113 FOREIGN KEY (way_id) REFERENCES ways (id)');
        $this->addSql('CREATE INDEX IDX_F2628E178C803113 ON disorders_ways (way_id)');
        $this->addSql('ALTER TABLE disorders_ways ADD PRIMARY KEY (disorder_id, way_id)');
        $this->addSql('ALTER TABLE domains ADD way_id INT DEFAULT NULL, DROP way');
        $this->addSql('ALTER TABLE domains ADD CONSTRAINT FK_8C7BBF9D8C803113 FOREIGN KEY (way_id) REFERENCES ways (id)');
        $this->addSql('CREATE INDEX IDX_8C7BBF9D8C803113 ON domains (way_id)');
        $this->addSql('DROP INDEX idxUnique ON traits');
        $this->addSql('ALTER TABLE traits ADD way_id INT DEFAULT NULL, DROP way');
        $this->addSql('ALTER TABLE traits ADD CONSTRAINT FK_E4A0A1668C803113 FOREIGN KEY (way_id) REFERENCES ways (id)');
        $this->addSql('CREATE INDEX IDX_E4A0A1668C803113 ON traits (way_id)');
        $this->addSql('CREATE UNIQUE INDEX idxUnique ON traits (name, way_id)');
    }
}
