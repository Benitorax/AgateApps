<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190105205142 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE events_foes DROP FOREIGN KEY FK_C15440C09D6A1065');
        $this->addSql('ALTER TABLE events_markers DROP FOREIGN KEY FK_17E8A62A9D6A1065');
        $this->addSql('ALTER TABLE events_markers_types DROP FOREIGN KEY FK_80FAF5E89D6A1065');
        $this->addSql('ALTER TABLE events_npcs DROP FOREIGN KEY FK_45C02BE49D6A1065');
        $this->addSql('ALTER TABLE events_resources DROP FOREIGN KEY FK_76928E8C9D6A1065');
        $this->addSql('ALTER TABLE events_routes DROP FOREIGN KEY FK_E068FB869D6A1065');
        $this->addSql('ALTER TABLE events_routes_types DROP FOREIGN KEY FK_7375FC699D6A1065');
        $this->addSql('ALTER TABLE events_weather DROP FOREIGN KEY FK_1AB1AA749D6A1065');
        $this->addSql('ALTER TABLE events_zones DROP FOREIGN KEY FK_3576794E9D6A1065');
        $this->addSql('ALTER TABLE events_zones_types DROP FOREIGN KEY FK_7E6C54359D6A1065');
        $this->addSql('ALTER TABLE events_foes DROP FOREIGN KEY FK_C15440C03DF0F043');
        $this->addSql('ALTER TABLE events_npcs DROP FOREIGN KEY FK_45C02BE473DFFBCA');
        $this->addSql('ALTER TABLE events_resources DROP FOREIGN KEY FK_76928E8CACFC5BFF');
        $this->addSql('ALTER TABLE resources_routes DROP FOREIGN KEY FK_389FB5C1ACFC5BFF');
        $this->addSql('ALTER TABLE resources_routes_types DROP FOREIGN KEY FK_1EC06A03ACFC5BFF');
        $this->addSql('ALTER TABLE resources_zones_types DROP FOREIGN KEY FK_161ED520ACFC5BFF');
        $this->addSql('ALTER TABLE events_weather DROP FOREIGN KEY FK_1AB1AA748CE675E');

        $this->addSql('DROP TABLE `events`');
        $this->addSql('DROP TABLE events_foes');
        $this->addSql('DROP TABLE events_markers');
        $this->addSql('DROP TABLE events_markers_types');
        $this->addSql('DROP TABLE events_npcs');
        $this->addSql('DROP TABLE events_resources');
        $this->addSql('DROP TABLE events_routes');
        $this->addSql('DROP TABLE events_routes_types');
        $this->addSql('DROP TABLE events_weather');
        $this->addSql('DROP TABLE events_zones');
        $this->addSql('DROP TABLE events_zones_types');
        $this->addSql('DROP TABLE maps_foes');
        $this->addSql('DROP TABLE maps_npcs');
        $this->addSql('DROP TABLE maps_resources');
        $this->addSql('DROP TABLE maps_weather');
        $this->addSql('DROP TABLE resources_routes');
        $this->addSql('DROP TABLE resources_routes_types');
        $this->addSql('DROP TABLE resources_zones_types');
        $this->addSql('ALTER TABLE avantages CHANGE is_desv is_disadvantage TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE avantages CHANGE augmentation_count bonus_count SMALLINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE avantages DROP is_combat_art');
        $this->addSql('ALTER TABLE used_vouchers CHANGE voucher_id voucher_id INT NOT NULL');
        $this->addSql('ALTER TABLE used_vouchers CHANGE user_id user_id INT NOT NULL');

        $this->addSql('ALTER TABLE characters_armors DROP INDEX IDX_91F54665333F27F1, ADD UNIQUE INDEX UNIQ_91F54665333F27F1 (armors_id)');
        $this->addSql('ALTER TABLE characters_armors DROP FOREIGN KEY FK_91F54665333F27F1');
        $this->addSql('ALTER TABLE characters_armors DROP FOREIGN KEY FK_91F54665C70F0E28');
        $this->addSql('ALTER TABLE characters_armors ADD CONSTRAINT FK_91F54665333F27F1 FOREIGN KEY (armors_id) REFERENCES armors (id)');
        $this->addSql('ALTER TABLE characters_armors ADD CONSTRAINT FK_91F54665C70F0E28 FOREIGN KEY (characters_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_artifacts DROP INDEX IDX_5908430338F3D9E1, ADD UNIQUE INDEX UNIQ_5908430338F3D9E1 (artifacts_id)');
        $this->addSql('ALTER TABLE characters_artifacts DROP FOREIGN KEY FK_5908430338F3D9E1');
        $this->addSql('ALTER TABLE characters_artifacts DROP FOREIGN KEY FK_59084303C70F0E28');
        $this->addSql('ALTER TABLE characters_artifacts ADD CONSTRAINT FK_5908430338F3D9E1 FOREIGN KEY (artifacts_id) REFERENCES artifacts (id)');
        $this->addSql('ALTER TABLE characters_artifacts ADD CONSTRAINT FK_59084303C70F0E28 FOREIGN KEY (characters_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_miracles DROP INDEX IDX_977340CA6B117C2B, ADD UNIQUE INDEX UNIQ_977340CA6B117C2B (miracles_id)');
        $this->addSql('ALTER TABLE characters_miracles DROP FOREIGN KEY FK_977340CA6B117C2B');
        $this->addSql('ALTER TABLE characters_miracles DROP FOREIGN KEY FK_977340CAC70F0E28');
        $this->addSql('ALTER TABLE characters_miracles ADD CONSTRAINT FK_977340CA6B117C2B FOREIGN KEY (miracles_id) REFERENCES miracles (id)');
        $this->addSql('ALTER TABLE characters_miracles ADD CONSTRAINT FK_977340CAC70F0E28 FOREIGN KEY (characters_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_ogham DROP INDEX IDX_53F779473241FF23, ADD UNIQUE INDEX UNIQ_53F779473241FF23 (ogham_id)');
        $this->addSql('ALTER TABLE characters_ogham DROP FOREIGN KEY FK_53F779473241FF23');
        $this->addSql('ALTER TABLE characters_ogham DROP FOREIGN KEY FK_53F77947C70F0E28');
        $this->addSql('ALTER TABLE characters_ogham ADD CONSTRAINT FK_53F779473241FF23 FOREIGN KEY (ogham_id) REFERENCES ogham (id)');
        $this->addSql('ALTER TABLE characters_ogham ADD CONSTRAINT FK_53F77947C70F0E28 FOREIGN KEY (characters_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_weapons DROP INDEX IDX_1A82C2BA2EE82581, ADD UNIQUE INDEX UNIQ_1A82C2BA2EE82581 (weapons_id)');
        $this->addSql('ALTER TABLE characters_weapons DROP FOREIGN KEY FK_1A82C2BA2EE82581');
        $this->addSql('ALTER TABLE characters_weapons DROP FOREIGN KEY FK_1A82C2BAC70F0E28');
        $this->addSql('ALTER TABLE characters_weapons ADD CONSTRAINT FK_1A82C2BA2EE82581 FOREIGN KEY (weapons_id) REFERENCES weapons (id)');
        $this->addSql('ALTER TABLE characters_weapons ADD CONSTRAINT FK_1A82C2BAC70F0E28 FOREIGN KEY (characters_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_combat_arts DROP INDEX IDX_4423FA342E5E3C78, ADD UNIQUE INDEX UNIQ_4423FA342E5E3C78 (combat_arts_id)');
        $this->addSql('ALTER TABLE characters_combat_arts DROP FOREIGN KEY FK_4423FA342E5E3C78');
        $this->addSql('ALTER TABLE characters_combat_arts DROP FOREIGN KEY FK_4423FA34C70F0E28');
        $this->addSql('ALTER TABLE characters_combat_arts ADD CONSTRAINT FK_4423FA342E5E3C78 FOREIGN KEY (combat_arts_id) REFERENCES combat_arts (id)');
        $this->addSql('ALTER TABLE characters_combat_arts ADD CONSTRAINT FK_4423FA34C70F0E28 FOREIGN KEY (characters_id) REFERENCES characters (id)');
    }

    public function down(Schema $schema) : void
    {
    }
}
